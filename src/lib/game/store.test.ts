import { afterEach, beforeEach, describe, expect, it } from "vitest";
import { createInitialSnapshot } from "@/lib/game/snapshot";
import { createTestBootstrap } from "@/lib/game/testUtils";
import {
  getSelectedVendorOptions,
  resetGameStore,
  sortVendorsByCost,
  useGameStore,
} from "@/lib/game/store";

describe("game store", () => {
  beforeEach(() => {
    resetGameStore();
  });

  afterEach(() => {
    resetGameStore();
  });

  it("initializes a fresh game with the first slot selected", () => {
    const bootstrap = createTestBootstrap();

    useGameStore.getState().initialize(bootstrap);
    const state = useGameStore.getState();

    expect(state.status).toBe("ready");
    expect(state.bootSource).toBe("new");
    expect(state.selectedSlotId).toBe(bootstrap.map.slots[0].id);
    expect(state.snapshot?.placedStalls).toEqual([]);
    expect(state.snapshot?.stats.accountBalance).toBe(
      bootstrap.initialEconomy.startingBalance,
    );
  });

  it("resumes an existing snapshot when one is supplied", () => {
    const bootstrap = createTestBootstrap();
    const snapshot = createInitialSnapshot(bootstrap);

    snapshot.placedStalls.push({
      slotId: bootstrap.map.slots[0].id,
      vendorId: bootstrap.vendors[0].id,
      openedAt: snapshot.stats.gameTimeSeconds,
      salesTotal: 125,
      customerCount: 8,
    });

    useGameStore.getState().initialize(bootstrap, snapshot);
    const state = useGameStore.getState();

    expect(state.bootSource).toBe("resume");
    expect(state.snapshot?.placedStalls).toHaveLength(1);
    expect(state.snapshot?.placedStalls[0].salesTotal).toBe(125);
  });

  it("places a vendor, deducts balance, and advances the simulation", () => {
    const bootstrap = createTestBootstrap({
      simulationConfig: {
        startingHour: 6,
        openTimeScale: 300,
        salesIntervalSeconds: 60,
      },
    });
    const vendor = sortVendorsByCost(
      getSelectedVendorOptions(bootstrap, "food"),
    )[0];

    useGameStore.getState().initialize(bootstrap);
    useGameStore.getState().selectSlot(bootstrap.map.slots[0].id);
    useGameStore.getState().selectCategory("food");
    useGameStore.getState().placeVendor(vendor.id);

    const placedState = useGameStore.getState();

    expect(placedState.snapshot?.placedStalls).toHaveLength(1);
    expect(placedState.snapshot?.stats.accountBalance).toBe(
      bootstrap.initialEconomy.startingBalance - vendor.buildCost,
    );
    expect(placedState.selectedCategorySlug).toBe("food");
    expect(placedState.autosaveState).toBe("dirty");

    useGameStore.getState().tick(1);
    const tickedState = useGameStore.getState();

    expect(tickedState.snapshot?.placedStalls[0].salesTotal).toBeGreaterThan(0);
    expect(tickedState.snapshot?.stats.salesBalance).toBeGreaterThan(0);
  });

  it("starts a new game from the current bootstrap and clears old stalls", () => {
    const bootstrap = createTestBootstrap();
    const snapshot = createInitialSnapshot(bootstrap);

    snapshot.stats.accountBalance = 100;
    snapshot.placedStalls.push({
      slotId: bootstrap.map.slots[0].id,
      vendorId: bootstrap.vendors[0].id,
      openedAt: snapshot.stats.gameTimeSeconds,
      salesTotal: 500,
      customerCount: 20,
    });

    useGameStore.getState().initialize(bootstrap, snapshot);
    useGameStore.getState().startNewGame();

    const state = useGameStore.getState();

    expect(state.bootSource).toBe("new");
    expect(state.snapshot?.placedStalls).toEqual([]);
    expect(state.snapshot?.stats.accountBalance).toBe(
      bootstrap.initialEconomy.startingBalance,
    );
  });
});
