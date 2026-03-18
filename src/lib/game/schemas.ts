import { z } from "zod";
import { GAME_BOOTSTRAP_VERSION, GAME_SNAPSHOT_VERSION } from "@/lib/game/constants";

export const CategorySlugSchema = z.enum([
  "food",
  "produce",
  "handmade",
  "provisions",
]);

export const StallSlotSchema = z.object({
  id: z.number().int(),
  x: z.number(),
  y: z.number(),
  rotation: z.number(),
  hasAwning: z.boolean(),
  label: z.string(),
});

export const CameraStateSchema = z.object({
  position: z.object({
    x: z.number(),
    y: z.number(),
    z: z.number(),
  }),
  target: z.object({
    x: z.number(),
    y: z.number(),
    z: z.number(),
  }),
});

export const CategorySeedSchema = z.object({
  slug: CategorySlugSchema,
  label: z.string(),
  icon: z.string(),
  color: z.string(),
});

export const ProductSeedSchema = z.object({
  id: z.string(),
  name: z.string(),
  price: z.number().positive(),
});

export const VendorScoresSchema = z.object({
  customerSatisfaction: z.number().min(0).max(100),
  productMargin: z.number().min(0).max(100),
  runningCosts: z.number().min(0).max(100),
  customerAppeal: z.number().min(0).max(100),
});

export const VendorTemplateSchema = z.object({
  id: z.string(),
  categorySlug: CategorySlugSchema,
  name: z.string(),
  contactName: z.string(),
  products: z.array(ProductSeedSchema).length(3),
  scores: VendorScoresSchema,
  buildCost: z.number().int().positive(),
});

export const GameBootstrapSchema = z.object({
  version: z.literal(GAME_BOOTSTRAP_VERSION),
  map: z.object({
    id: z.string(),
    label: z.string(),
    bounds: z.object({
      minX: z.number(),
      maxX: z.number(),
      minY: z.number(),
      maxY: z.number(),
    }),
    slots: z.array(StallSlotSchema).min(1),
  }),
  categories: z.array(CategorySeedSchema).length(4),
  vendors: z.array(VendorTemplateSchema).length(12),
  simulationConfig: z.object({
    marketOpenHour: z.number().int(),
    marketCloseHour: z.number().int(),
    startingHour: z.number(),
    openTimeScale: z.number().positive(),
    closedTimeScale: z.number().positive(),
    salesIntervalSeconds: z.number().positive(),
    autosaveIntervalMs: z.number().positive(),
  }),
  initialEconomy: z.object({
    startingBalance: z.number().positive(),
    camera: CameraStateSchema,
  }),
});

export const PlacedStallSchema = z.object({
  slotId: z.number().int(),
  vendorId: z.string(),
  openedAt: z.number().min(0),
  salesTotal: z.number().min(0),
  customerCount: z.number().int().min(0),
});

export const GameSnapshotSchema = z.object({
  version: z.literal(GAME_SNAPSHOT_VERSION),
  updatedAt: z.string(),
  camera: CameraStateSchema,
  stats: z.object({
    startedAt: z.string(),
    gameTimeSeconds: z.number().min(0),
    accountBalance: z.number().min(0),
    salesBalance: z.number().min(0),
    customerCount: z.number().int().min(0),
  }),
  placedStalls: z.array(PlacedStallSchema),
});

export type CategorySlug = z.infer<typeof CategorySlugSchema>;
export type StallSlot = z.infer<typeof StallSlotSchema>;
export type CameraState = z.infer<typeof CameraStateSchema>;
export type CategorySeed = z.infer<typeof CategorySeedSchema>;
export type ProductSeed = z.infer<typeof ProductSeedSchema>;
export type VendorScores = z.infer<typeof VendorScoresSchema>;
export type VendorTemplate = z.infer<typeof VendorTemplateSchema>;
export type GameBootstrap = z.infer<typeof GameBootstrapSchema>;
export type PlacedStall = z.infer<typeof PlacedStallSchema>;
export type GameSnapshot = z.infer<typeof GameSnapshotSchema>;

export interface GameStorageAdapter {
  loadLatest(): GameSnapshot | null;
  saveLatest(snapshot: GameSnapshot): void;
  clear(): void;
}
