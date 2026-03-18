import fs from "node:fs";
import path from "node:path";
import { extractMapSlots } from "../src/server/game/extractors/legacyDump";

const repoRoot = process.cwd();
const sqlPath = path.join(repoRoot, "deploy-db.sql");
const outputPath = path.join(
  repoRoot,
  "src/server/game/seeds/generated/map-slots.ts",
);

const sql = fs.readFileSync(sqlPath, "utf8");

const slots = extractMapSlots(sql, 47).map((slot) => ({
  id: slot.id,
  x: round((slot.x - 1500) * 0.3),
  y: round((slot.y - 1350) * 0.3),
  rotation: slot.rotation,
  hasAwning: slot.hasAwning,
  label: slot.label,
}));

const fileContents = `import type { StallSlot } from "@/lib/game/schemas";

// Generated from deploy-db.sql by scripts/generate-map-slots.ts
export const generatedMapSlots: StallSlot[] = ${JSON.stringify(slots, null, 2)} as StallSlot[];
`;

fs.mkdirSync(path.dirname(outputPath), { recursive: true });
fs.writeFileSync(outputPath, fileContents);

function round(value: number) {
  return Math.round(value * 100) / 100;
}
