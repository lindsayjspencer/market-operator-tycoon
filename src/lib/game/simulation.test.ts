import { describe, expect, it } from "vitest";
import { createInitialSnapshot } from "@/lib/game/snapshot";
import {
  computeCustomerCount,
  formatClock,
  formatDay,
  getHourOfDay,
  getMarketStatusLabel,
  isMarketOpen,
  simulateStep,
} from "@/lib/game/simulation";
import { createTestBootstrap } from "@/lib/game/testUtils";

describe("simulation helpers", () => {
  it("formats time, day, and open state correctly", () => {
    const bootstrap = createTestBootstrap();

    expect(getHourOfDay(25 * 3600)).toBe(1);
    expect(formatClock(13.5 * 3600)).toBe("01:30 PM");
    expect(formatDay(24 * 3600)).toBe("Day 2");
    expect(isMarketOpen(5.9 * 3600, bootstrap.simulationConfig)).toBe(false);
    expect(isMarketOpen(6 * 3600, bootstrap.simulationConfig)).toBe(true);
    expect(getMarketStatusLabel(7 * 3600, bootstrap.simulationConfig)).toBe("Open");
  });

  it("advances the economy for placed stalls during open market intervals", () => {
    const bootstrap = createTestBootstrap({
      simulationConfig: {
        startingHour: 6,
        openTimeScale: 300,
        salesIntervalSeconds: 60,
      },
    });
    const vendor = bootstrap.vendors[0];
    const snapshot = createInitialSnapshot(bootstrap);

    snapshot.stats.accountBalance -= vendor.buildCost;
    snapshot.placedStalls.push({
      slotId: bootstrap.map.slots[0].id,
      vendorId: vendor.id,
      openedAt: snapshot.stats.gameTimeSeconds,
      salesTotal: 0,
      customerCount: 0,
    });

    const next = simulateStep(snapshot, bootstrap, 1);

    expect(next.stats.gameTimeSeconds).toBeGreaterThan(snapshot.stats.gameTimeSeconds);
    expect(next.stats.accountBalance).toBeGreaterThan(snapshot.stats.accountBalance);
    expect(next.stats.salesBalance).toBeGreaterThan(0);
    expect(next.placedStalls[0].salesTotal).toBeGreaterThan(0);
    expect(next.placedStalls[0].customerCount).toBeGreaterThan(0);
    expect(
      computeCustomerCount(
        next.stats.gameTimeSeconds,
        next.placedStalls,
        new Map(bootstrap.vendors.map((entry) => [entry.id, entry])),
      ),
    ).toBeGreaterThan(0);
  });

  it("resets daily sales when the simulation rolls into a new day", () => {
    const bootstrap = createTestBootstrap({
      simulationConfig: {
        openTimeScale: 60,
        closedTimeScale: 60,
      },
    });
    const snapshot = createInitialSnapshot(bootstrap);

    snapshot.stats.gameTimeSeconds = 24 * 3600 - 30;
    snapshot.stats.salesBalance = 420;

    const next = simulateStep(snapshot, bootstrap, 1);

    expect(formatDay(next.stats.gameTimeSeconds)).toBe("Day 2");
    expect(next.stats.salesBalance).toBe(0);
  });
});
