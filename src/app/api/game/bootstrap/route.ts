import { NextResponse } from "next/server";
import { getGameBootstrap } from "@/server/game/bootstrap";

export async function GET() {
  return NextResponse.json(getGameBootstrap());
}
