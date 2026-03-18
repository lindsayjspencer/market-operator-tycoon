// @vitest-environment node

import { describe, expect, it, vi } from "vitest";
import { GameBootstrapSchema } from "@/lib/game/schemas";

vi.mock("server-only", () => ({}));

import { getGameBootstrap } from "@/server/game/bootstrap";

describe("getGameBootstrap", () => {
  it("returns the validated bootstrap contract", () => {
    const bootstrap = GameBootstrapSchema.parse(getGameBootstrap());

    expect(bootstrap.version).toBe(1);
    expect(bootstrap.map.id).toBe("milton-market-47");
    expect(bootstrap.map.slots).toHaveLength(169);
    expect(bootstrap.categories).toHaveLength(4);
    expect(bootstrap.vendors).toHaveLength(12);
    expect(new Set(bootstrap.vendors.map((vendor) => vendor.categorySlug))).toEqual(
      new Set(["food", "produce", "handmade", "provisions"]),
    );
  });
});
