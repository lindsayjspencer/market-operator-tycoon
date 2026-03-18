"use client";

import { create } from "zustand";
import {
  type CameraState,
  type CategorySlug,
  type GameBootstrap,
  type GameSnapshot,
  type VendorTemplate,
} from "@/lib/game/schemas";
import { createInitialSnapshot } from "@/lib/game/snapshot";
import { simulateStep } from "@/lib/game/simulation";

type BootSource = "new" | "resume" | null;
type AutosaveState = "idle" | "dirty" | "saved";

interface GameStoreState {
  status: "loading" | "ready" | "error";
  error: string | null;
  bootstrap: GameBootstrap | null;
  snapshot: GameSnapshot | null;
  selectedSlotId: number | null;
  selectedCategorySlug: CategorySlug | null;
  autosaveState: AutosaveState;
  bootSource: BootSource;
  initialize: (bootstrap: GameBootstrap, snapshot?: GameSnapshot | null) => void;
  startNewGame: () => void;
  selectSlot: (slotId: number | null) => void;
  selectCategory: (categorySlug: CategorySlug | null) => void;
  placeVendor: (vendorId: string) => void;
  tick: (realStepSeconds: number) => void;
  setCamera: (camera: CameraState) => void;
  markAutosaved: () => void;
  setError: (message: string) => void;
}

function createStoreDefaults() {
  return {
    status: "loading" as const,
    error: null,
    bootstrap: null,
    snapshot: null,
    selectedSlotId: null,
    selectedCategorySlug: null,
    autosaveState: "idle" as const,
    bootSource: null as BootSource,
  };
}

export const useGameStore = create<GameStoreState>((set, get) => ({
  ...createStoreDefaults(),

  initialize: (bootstrap, snapshot) => {
    const resolvedSnapshot = snapshot ?? createInitialSnapshot(bootstrap);

    set({
      status: "ready",
      error: null,
      bootstrap,
      snapshot: resolvedSnapshot,
      selectedSlotId: bootstrap.map.slots[0]?.id ?? null,
      selectedCategorySlug: null,
      autosaveState: "dirty",
      bootSource: snapshot ? "resume" : "new",
    });
  },

  startNewGame: () => {
    const bootstrap = get().bootstrap;
    if (!bootstrap) {
      return;
    }

    set({
      snapshot: createInitialSnapshot(bootstrap),
      selectedSlotId: bootstrap.map.slots[0]?.id ?? null,
      selectedCategorySlug: null,
      autosaveState: "dirty",
      bootSource: "new",
    });
  },

  selectSlot: (slotId) => {
    set({ selectedSlotId: slotId });
  },

  selectCategory: (selectedCategorySlug) => {
    set({ selectedCategorySlug });
  },

  placeVendor: (vendorId) => {
    const bootstrap = get().bootstrap;
    const snapshot = get().snapshot;
    const slotId = get().selectedSlotId;

    if (!bootstrap || !snapshot || slotId === null) {
      return;
    }

    const slotAlreadyTaken = snapshot.placedStalls.some((stall) => stall.slotId === slotId);
    const vendor = bootstrap.vendors.find((entry) => entry.id === vendorId);

    if (!vendor || slotAlreadyTaken || snapshot.stats.accountBalance < vendor.buildCost) {
      return;
    }

    set({
      snapshot: {
        ...snapshot,
        updatedAt: new Date().toISOString(),
        stats: {
          ...snapshot.stats,
          accountBalance: snapshot.stats.accountBalance - vendor.buildCost,
        },
        placedStalls: [
          ...snapshot.placedStalls,
          {
            slotId,
            vendorId: vendor.id,
            openedAt: snapshot.stats.gameTimeSeconds,
            salesTotal: 0,
            customerCount: 0,
          },
        ],
      },
      selectedCategorySlug: vendor.categorySlug,
      autosaveState: "dirty",
    });
  },

  tick: (realStepSeconds) => {
    const bootstrap = get().bootstrap;
    const snapshot = get().snapshot;

    if (!bootstrap || !snapshot) {
      return;
    }

    set({
      snapshot: simulateStep(snapshot, bootstrap, realStepSeconds),
    });
  },

  setCamera: (camera) => {
    const snapshot = get().snapshot;
    if (!snapshot) {
      return;
    }

    set({
      snapshot: {
        ...snapshot,
        updatedAt: new Date().toISOString(),
        camera,
      },
      autosaveState: "dirty",
    });
  },

  markAutosaved: () => set({ autosaveState: "saved" }),
  setError: (error) => set({ status: "error", error }),
}));

export function resetGameStore() {
  useGameStore.setState(createStoreDefaults());
}

export function getSelectedVendorOptions(
  bootstrap: GameBootstrap | null,
  categorySlug: CategorySlug | null,
) {
  if (!bootstrap || !categorySlug) {
    return [];
  }

  return bootstrap.vendors.filter((vendor) => vendor.categorySlug === categorySlug);
}

export function getPlacedVendor(
  bootstrap: GameBootstrap | null,
  snapshot: GameSnapshot | null,
  slotId: number | null,
) {
  if (!bootstrap || !snapshot || slotId === null) {
    return null;
  }

  const placedStall = snapshot.placedStalls.find((stall) => stall.slotId === slotId);
  if (!placedStall) {
    return null;
  }

  const vendor = bootstrap.vendors.find((entry) => entry.id === placedStall.vendorId);
  if (!vendor) {
    return null;
  }

  return {
    placedStall,
    vendor,
  };
}

export function getCategoryIndex(bootstrap: GameBootstrap | null) {
  return new Map(
    (bootstrap?.categories ?? []).map((category) => [category.slug, category]),
  );
}

export function getVendorIndex(bootstrap: GameBootstrap | null) {
  return new Map((bootstrap?.vendors ?? []).map((vendor) => [vendor.id, vendor]));
}

export function sortVendorsByCost(vendors: VendorTemplate[]) {
  return [...vendors].sort((left, right) => left.buildCost - right.buildCost);
}
