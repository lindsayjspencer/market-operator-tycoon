import "server-only";

import { GameBootstrapSchema, type GameBootstrap } from "@/lib/game/schemas";
import { categories } from "@/server/game/seeds/categories";
import { curatedVendors } from "@/server/game/seeds/curated-vendors";
import { generatedMapSlots } from "@/server/game/seeds/generated/map-slots";

export function getGameBootstrap(): GameBootstrap {
  const bounds = generatedMapSlots.reduce(
    (accumulator, slot) => ({
      minX: Math.min(accumulator.minX, slot.x),
      maxX: Math.max(accumulator.maxX, slot.x),
      minY: Math.min(accumulator.minY, slot.y),
      maxY: Math.max(accumulator.maxY, slot.y),
    }),
    {
      minX: Number.POSITIVE_INFINITY,
      maxX: Number.NEGATIVE_INFINITY,
      minY: Number.POSITIVE_INFINITY,
      maxY: Number.NEGATIVE_INFINITY,
    },
  );

  return GameBootstrapSchema.parse({
    version: 1,
    map: {
      id: "milton-market-47",
      label: "Milton Market",
      bounds,
      slots: generatedMapSlots,
    },
    categories,
    vendors: curatedVendors,
    simulationConfig: {
      marketOpenHour: 6,
      marketCloseHour: 12,
      startingHour: 5.5,
      openTimeScale: 120,
      closedTimeScale: 1200,
      salesIntervalSeconds: 300,
      autosaveIntervalMs: 3000,
    },
    initialEconomy: {
      startingBalance: 25000,
      camera: {
        position: { x: 40, y: 52, z: 100 },
        target: { x: 0, y: 0, z: 0 },
      },
    },
  });
}
