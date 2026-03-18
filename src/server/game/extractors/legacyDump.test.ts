import { describe, expect, it } from "vitest";
import {
  extractInsertTuples,
  extractMapSlots,
  parseSqlTuple,
} from "@/server/game/extractors/legacyDump";

const sampleSql = `
DROP TABLE IF EXISTS \`cerb_map_loc\`;
INSERT INTO \`cerb_map_loc\` VALUES
(6747,0,1402.2,1015.3,47,'S1',180,0,0,0),
(6751,0,1489.03,1138.39,47,'Fruit, Veg',90,0,0,1),
(7000,0,1600,1200,99,'Overflow',0,0,0,0);
`;

describe("legacy SQL extraction", () => {
  it("parses tuples with strings, commas, nulls, and escaped quotes", () => {
    expect(parseSqlTuple("1,NULL,'Fruit, Veg','O\\'Brien'")).toEqual([
      "1",
      null,
      "Fruit, Veg",
      "O'Brien",
    ]);
  });

  it("extracts insert tuples for a named table", () => {
    expect(extractInsertTuples(sampleSql, "cerb_map_loc")).toHaveLength(3);
  });

  it("extracts and filters map slots for one playable map", () => {
    expect(extractMapSlots(sampleSql, 47)).toEqual([
      {
        id: 6747,
        x: 1402.2,
        y: 1015.3,
        mapId: 47,
        label: "S1",
        rotation: 180,
        hasAwning: false,
      },
      {
        id: 6751,
        x: 1489.03,
        y: 1138.39,
        mapId: 47,
        label: "Fruit, Veg",
        rotation: 90,
        hasAwning: true,
      },
    ]);
  });
});
