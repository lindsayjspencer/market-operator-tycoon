import type {
  GameBootstrap,
  GameSnapshot,
  PlacedStall,
  StallSlot,
  VendorTemplate,
} from "@/lib/game/schemas";

const SECONDS_IN_DAY = 24 * 60 * 60;

export function getHourOfDay(gameTimeSeconds: number) {
  const timeOfDay = modulo(gameTimeSeconds, SECONDS_IN_DAY);
  return timeOfDay / 3600;
}

export function isMarketOpen(
  gameTimeSeconds: number,
  config: GameBootstrap["simulationConfig"],
) {
  const hour = getHourOfDay(gameTimeSeconds);
  return hour >= config.marketOpenHour && hour < config.marketCloseHour;
}

export function getMarketStatusLabel(
  gameTimeSeconds: number,
  config: GameBootstrap["simulationConfig"],
) {
  return isMarketOpen(gameTimeSeconds, config) ? "Open" : "Closed";
}

export function getTimeScale(
  gameTimeSeconds: number,
  config: GameBootstrap["simulationConfig"],
) {
  return isMarketOpen(gameTimeSeconds, config)
    ? config.openTimeScale
    : config.closedTimeScale;
}

export function formatClock(gameTimeSeconds: number) {
  const totalSeconds = Math.floor(modulo(gameTimeSeconds, SECONDS_IN_DAY));
  let hours = Math.floor(totalSeconds / 3600);
  const minutes = Math.floor((totalSeconds % 3600) / 60);
  const suffix = hours >= 12 ? "PM" : "AM";

  if (hours === 0) {
    hours = 12;
  } else if (hours > 12) {
    hours -= 12;
  }

  return `${hours.toString().padStart(2, "0")}:${minutes
    .toString()
    .padStart(2, "0")} ${suffix}`;
}

export function formatDay(gameTimeSeconds: number) {
  return `Day ${Math.floor(gameTimeSeconds / SECONDS_IN_DAY) + 1}`;
}

export function simulateStep(
  snapshot: GameSnapshot,
  bootstrap: GameBootstrap,
  realStepSeconds: number,
): GameSnapshot {
  const previousGameTime = snapshot.stats.gameTimeSeconds;
  const deltaGameSeconds =
    realStepSeconds * getTimeScale(previousGameTime, bootstrap.simulationConfig);
  const nextGameTime = previousGameTime + deltaGameSeconds;
  const previousDayIndex = Math.floor(previousGameTime / SECONDS_IN_DAY);
  const nextDayIndex = Math.floor(nextGameTime / SECONDS_IN_DAY);

  let placedStalls = snapshot.placedStalls.map((stall) => ({ ...stall }));
  let accountBalance = snapshot.stats.accountBalance;
  let salesBalance =
    nextDayIndex === previousDayIndex ? snapshot.stats.salesBalance : 0;

  const vendorIndex = new Map(bootstrap.vendors.map((vendor) => [vendor.id, vendor]));
  const slotIndex = new Map(bootstrap.map.slots.map((slot) => [slot.id, slot]));
  const previousInterval = Math.floor(
    previousGameTime / bootstrap.simulationConfig.salesIntervalSeconds,
  );
  const nextInterval = Math.floor(
    nextGameTime / bootstrap.simulationConfig.salesIntervalSeconds,
  );

  for (let interval = previousInterval + 1; interval <= nextInterval; interval += 1) {
    const intervalTime = interval * bootstrap.simulationConfig.salesIntervalSeconds;
    if (!isMarketOpen(intervalTime, bootstrap.simulationConfig)) {
      continue;
    }

    const crowd = computeCustomerCount(intervalTime, placedStalls, vendorIndex);
    placedStalls = placedStalls.map((stall) => {
      const vendor = vendorIndex.get(stall.vendorId);
      const slot = slotIndex.get(stall.slotId);

      if (!vendor || !slot) {
        return stall;
      }

      const intervalOutcome = computeIntervalOutcome(
        stall,
        vendor,
        slot,
        crowd,
        placedStalls.length,
      );

      accountBalance = roundCurrency(accountBalance + intervalOutcome.revenue);
      salesBalance = roundCurrency(salesBalance + intervalOutcome.revenue);

      return {
        ...stall,
        salesTotal: roundCurrency(stall.salesTotal + intervalOutcome.revenue),
        customerCount: stall.customerCount + intervalOutcome.customers,
      };
    });
  }

  return {
    ...snapshot,
    updatedAt: new Date().toISOString(),
    stats: {
      ...snapshot.stats,
      gameTimeSeconds: nextGameTime,
      accountBalance,
      salesBalance,
      customerCount: computeCustomerCount(nextGameTime, placedStalls, vendorIndex),
    },
    placedStalls,
  };
}

export function computeCustomerCount(
  gameTimeSeconds: number,
  placedStalls: PlacedStall[],
  vendorIndex: Map<string, VendorTemplate>,
) {
  if (placedStalls.length === 0 || getHourOfDay(gameTimeSeconds) < 6) {
    return 0;
  }

  const hour = getHourOfDay(gameTimeSeconds);
  const marketCurve = Math.sin(((hour - 6) / 6) * Math.PI);
  const effectiveCurve = Number.isNaN(marketCurve) ? 0 : Math.max(0, marketCurve);
  const averageAppeal =
    placedStalls.reduce((sum, stall) => {
      const vendor = vendorIndex.get(stall.vendorId);
      return sum + (vendor?.scores.customerAppeal ?? 0);
    }, 0) / placedStalls.length;

  const baseCrowd = 14 + placedStalls.length * 6;
  const crowdBoost = effectiveCurve * (8 + averageAppeal / 10);

  return Math.max(0, Math.round(baseCrowd + crowdBoost));
}

function computeIntervalOutcome(
  stall: PlacedStall,
  vendor: VendorTemplate,
  slot: StallSlot,
  crowd: number,
  stallCount: number,
) {
  const averagePrice =
    vendor.products.reduce((sum, product) => sum + product.price, 0) /
    vendor.products.length;
  const qualityScore =
    vendor.scores.customerSatisfaction * 0.4 +
    vendor.scores.productMargin * 0.2 +
    vendor.scores.customerAppeal * 0.4;
  const qualityMultiplier = qualityScore / 100;
  const costControl = 1 - vendor.scores.runningCosts / 145;
  const competitionFactor = Math.max(0.74, 1 - (stallCount - 1) * 0.018);
  const awningBonus = slot.hasAwning ? 1.04 : 1;
  const customerShare = Math.max(
    1,
    Math.round(
      ((crowd / Math.max(1, stallCount)) * (qualityMultiplier + 0.35) * awningBonus) /
        8,
    ),
  );
  const revenue = roundCurrency(
    customerShare *
      averagePrice *
      (0.48 + vendor.scores.productMargin / 115) *
      Math.max(0.35, costControl) *
      competitionFactor,
  );

  return {
    customers: customerShare,
    revenue: stall.salesTotal > 0 ? revenue : roundCurrency(revenue * 0.92),
  };
}

function modulo(value: number, divisor: number) {
  return ((value % divisor) + divisor) % divisor;
}

function roundCurrency(value: number) {
  return Math.round(value * 100) / 100;
}
