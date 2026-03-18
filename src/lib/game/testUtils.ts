import { GAME_BOOTSTRAP_VERSION } from "@/lib/game/constants";
import { GameBootstrapSchema, type GameBootstrap } from "@/lib/game/schemas";
import { categories } from "@/server/game/seeds/categories";
import { curatedVendors } from "@/server/game/seeds/curated-vendors";
import { generatedMapSlots } from "@/server/game/seeds/generated/map-slots";

type DeepPartial<T> = {
  [Key in keyof T]?: T[Key] extends Array<infer Item>
    ? Array<DeepPartial<Item>>
    : T[Key] extends object
      ? DeepPartial<T[Key]>
      : T[Key];
};

export function createTestBootstrap(
  overrides?: DeepPartial<GameBootstrap>,
): GameBootstrap {
  const slots = structuredClone(generatedMapSlots.slice(0, 4));
  const bounds = slots.reduce(
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

  const base: GameBootstrap = {
    version: GAME_BOOTSTRAP_VERSION,
    map: {
      id: "test-market",
      label: "Test Market",
      bounds,
      slots,
    },
    categories: structuredClone(categories),
    vendors: structuredClone(curatedVendors),
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
  };

  return GameBootstrapSchema.parse({
    ...base,
    ...overrides,
    map: {
      ...base.map,
      ...overrides?.map,
    },
    simulationConfig: {
      ...base.simulationConfig,
      ...overrides?.simulationConfig,
    },
    initialEconomy: {
      ...base.initialEconomy,
      ...overrides?.initialEconomy,
      camera: {
        position: {
          ...base.initialEconomy.camera.position,
          ...overrides?.initialEconomy?.camera?.position,
        },
        target: {
          ...base.initialEconomy.camera.target,
          ...overrides?.initialEconomy?.camera?.target,
        },
      },
    },
  });
}
