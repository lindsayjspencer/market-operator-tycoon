import { GAME_SNAPSHOT_VERSION } from "@/lib/game/constants";
import {
  type CameraState,
  type GameBootstrap,
  type GameSnapshot,
  GameSnapshotSchema,
} from "@/lib/game/schemas";

export function createInitialSnapshot(bootstrap: GameBootstrap): GameSnapshot {
  return {
    version: GAME_SNAPSHOT_VERSION,
    updatedAt: new Date().toISOString(),
    camera: {
      position: { ...bootstrap.initialEconomy.camera.position },
      target: { ...bootstrap.initialEconomy.camera.target },
    },
    stats: {
      startedAt: new Date().toISOString(),
      gameTimeSeconds: bootstrap.simulationConfig.startingHour * 3600,
      accountBalance: bootstrap.initialEconomy.startingBalance,
      salesBalance: 0,
      customerCount: 0,
    },
    placedStalls: [],
  };
}

export function serializeSnapshot(snapshot: GameSnapshot): string {
  return JSON.stringify(GameSnapshotSchema.parse(snapshot));
}

export function deserializeSnapshot(raw: string): GameSnapshot | null {
  try {
    return GameSnapshotSchema.parse(JSON.parse(raw));
  } catch {
    return null;
  }
}

export function withUpdatedSnapshot(
  snapshot: GameSnapshot,
  updates: Partial<GameSnapshot>,
): GameSnapshot {
  return GameSnapshotSchema.parse({
    ...snapshot,
    ...updates,
    updatedAt: new Date().toISOString(),
  });
}

export function withCamera(snapshot: GameSnapshot, camera: CameraState): GameSnapshot {
  return withUpdatedSnapshot(snapshot, { camera });
}
