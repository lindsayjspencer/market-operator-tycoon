import { GAME_STORAGE_KEY } from "@/lib/game/constants";
import {
  type GameSnapshot,
  type GameStorageAdapter,
} from "@/lib/game/schemas";
import { deserializeSnapshot, serializeSnapshot } from "@/lib/game/snapshot";

export const localGameStorage: GameStorageAdapter = {
  loadLatest(): GameSnapshot | null {
    if (typeof window === "undefined") {
      return null;
    }

    const raw = window.localStorage.getItem(GAME_STORAGE_KEY);
    if (!raw) {
      return null;
    }

    return deserializeSnapshot(raw);
  },

  saveLatest(snapshot: GameSnapshot) {
    if (typeof window === "undefined") {
      return;
    }

    window.localStorage.setItem(GAME_STORAGE_KEY, serializeSnapshot(snapshot));
  },

  clear() {
    if (typeof window === "undefined") {
      return;
    }

    window.localStorage.removeItem(GAME_STORAGE_KEY);
  },
};
