# Market Operator Tycoon

Next.js and TypeScript rebuild of the original PHP market-management sim. The legacy app remains in [`www/`](./www/) as reference only; the new proof of concept lives at the repo root and is designed for Vercel deployment.

## Stack

- Next.js App Router
- TypeScript in `strict` mode
- `pnpm`
- React Three Fiber / Three.js
- Zustand for game state
- Zod for runtime validation
- Vitest and Playwright for coverage

## What The POC Includes

- Legacy Milton Market slot layout extracted from `deploy-db.sql`
- Four simplified categories: `food`, `produce`, `handmade`, `provisions`
- Three curated vendor templates per category
- Client-side stall placement, market clock, revenue simulation, camera persistence, and local autosave resume
- Server-owned bootstrap payload at `GET /api/game/bootstrap`

## Development

```bash
pnpm install
pnpm dev
```

Useful commands:

```bash
pnpm lint
pnpm typecheck
pnpm test
pnpm test:e2e
pnpm build
pnpm generate:map-seeds
```

## Data Model

The new domain model intentionally ignores most of the legacy database. Only the concepts required to run the game are carried forward:

- map slot geometry
- category metadata
- vendor templates
- product labels and prices
- vendor score inputs
- simulation config
- local snapshot state

The SQL dump is reference material only. Future persistence can be added behind the current TypeScript contracts without reviving the old schema.

## Deployment

Deployment is owned by Vercel. Connect the repo in Vercel and let its Git integration handle preview and production deploys.

Recommended Vercel setup:

- Root Directory: repository root
- Framework Preset: Next.js
- Keep legacy-only content out of the deployment upload with [`.vercelignore`](./.vercelignore)

GitHub Actions is CI only and now runs:

- lint
- typecheck
- unit/integration tests
- production build
- Playwright smoke tests
