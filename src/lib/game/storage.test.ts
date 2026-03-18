import { describe, expect, it } from "vitest";
import { createInitialSnapshot } from "@/lib/game/snapshot";
import { localGameStorage } from "@/lib/game/storage";
import { createTestBootstrap } from "@/lib/game/testUtils";

describe("localGameStorage", () => {
  it("saves, loads, and clears the latest snapshot", () => {
    const snapshot = createInitialSnapshot(createTestBootstrap());

    expect(localGameStorage.loadLatest()).toBeNull();

    localGameStorage.saveLatest(snapshot);
    expect(localGameStorage.loadLatest()).toEqual(snapshot);

    localGameStorage.clear();
    expect(localGameStorage.loadLatest()).toBeNull();
  });
});
