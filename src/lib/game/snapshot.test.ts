import { describe, expect, it } from "vitest";
import { GAME_SNAPSHOT_VERSION } from "@/lib/game/constants";
import {
  createInitialSnapshot,
  deserializeSnapshot,
  serializeSnapshot,
  withCamera,
} from "@/lib/game/snapshot";
import { createTestBootstrap } from "@/lib/game/testUtils";

describe("snapshot helpers", () => {
  it("creates an initial snapshot from bootstrap defaults", () => {
    const bootstrap = createTestBootstrap({
      simulationConfig: { startingHour: 6.5 },
      initialEconomy: {
        startingBalance: 12345,
        camera: {
          position: { x: 10, y: 20, z: 30 },
          target: { x: 1, y: 2, z: 3 },
        },
      },
    });

    const snapshot = createInitialSnapshot(bootstrap);

    expect(snapshot.version).toBe(GAME_SNAPSHOT_VERSION);
    expect(snapshot.stats.gameTimeSeconds).toBe(23400);
    expect(snapshot.stats.accountBalance).toBe(12345);
    expect(snapshot.camera).toEqual(bootstrap.initialEconomy.camera);
    expect(snapshot.camera).not.toBe(bootstrap.initialEconomy.camera);
    expect(snapshot.placedStalls).toEqual([]);
  });

  it("serializes and deserializes a valid snapshot", () => {
    const snapshot = createInitialSnapshot(createTestBootstrap());

    expect(deserializeSnapshot(serializeSnapshot(snapshot))).toEqual(snapshot);
  });

  it("returns null for malformed or version-mismatched snapshots", () => {
    expect(deserializeSnapshot("{bad json")).toBeNull();
    expect(
      deserializeSnapshot(
        JSON.stringify({
          ...createInitialSnapshot(createTestBootstrap()),
          version: 999,
        }),
      ),
    ).toBeNull();
  });

  it("updates the snapshot camera and refreshes the timestamp", () => {
    const snapshot = {
      ...createInitialSnapshot(createTestBootstrap()),
      updatedAt: "2020-01-01T00:00:00.000Z",
    };

    const next = withCamera(snapshot, {
      position: { x: 8, y: 9, z: 10 },
      target: { x: 0, y: 1, z: 2 },
    });

    expect(next.camera.position).toEqual({ x: 8, y: 9, z: 10 });
    expect(next.camera.target).toEqual({ x: 0, y: 1, z: 2 });
    expect(next.updatedAt).not.toBe(snapshot.updatedAt);
  });
});
