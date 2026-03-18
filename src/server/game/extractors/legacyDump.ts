export interface LegacyMapSlot {
  id: number;
  x: number;
  y: number;
  mapId: number;
  label: string;
  rotation: number;
  hasAwning: boolean;
}

export function parseSqlTuple(tuple: string): Array<string | null> {
  const values: Array<string | null> = [];
  let current = "";
  let inString = false;

  for (let index = 0; index < tuple.length; index += 1) {
    const character = tuple[index];
    const previous = tuple[index - 1];

    if (character === "'" && previous !== "\\") {
      inString = !inString;
      continue;
    }

    if (character === "," && !inString) {
      values.push(normalizeSqlValue(current));
      current = "";
      continue;
    }

    current += character;
  }

  values.push(normalizeSqlValue(current));
  return values;
}

export function extractInsertTuples(sql: string, tableName: string): string[] {
  const pattern = new RegExp(`INSERT INTO \`${tableName}\` VALUES\\s*([\\s\\S]*?);`);
  const match = sql.match(pattern);

  if (!match) {
    throw new Error(`Could not find INSERT block for ${tableName}.`);
  }

  return [...match[1].matchAll(/\(([^()]*)\)/g)].map((entry) => entry[1]);
}

export function extractMapSlots(sql: string, mapId: number): LegacyMapSlot[] {
  return extractInsertTuples(sql, "cerb_map_loc")
    .map(parseSqlTuple)
    .map((columns) => ({
      id: Number(columns[0]),
      x: Number(columns[2]),
      y: Number(columns[3]),
      mapId: Number(columns[4]),
      label: columns[5] ?? "",
      rotation: Number(columns[6] ?? 0),
      hasAwning: Number(columns[9] ?? 0) === 1,
    }))
    .filter((slot) => slot.mapId === mapId)
    .sort((left, right) => left.id - right.id);
}

function normalizeSqlValue(value: string): string | null {
  const trimmed = value.trim();

  if (trimmed === "NULL") {
    return null;
  }

  return trimmed.replace(/\\'/g, "'");
}
