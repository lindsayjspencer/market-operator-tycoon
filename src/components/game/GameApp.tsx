"use client";

import Image from "next/image";
import { useEffect, useEffectEvent, useRef, useState } from "react";
import GameCanvas from "@/components/game/GameCanvas";
import styles from "@/components/game/GameApp.module.css";
import { GAME_FIXED_STEP_SECONDS } from "@/lib/game/constants";
import {
  type CategorySlug,
  type GameBootstrap,
  GameBootstrapSchema,
} from "@/lib/game/schemas";
import { localGameStorage } from "@/lib/game/storage";
import {
  formatClock,
  formatDay,
  getMarketStatusLabel,
  isMarketOpen,
} from "@/lib/game/simulation";
import {
  getCategoryIndex,
  getPlacedVendor,
  getSelectedVendorOptions,
  sortVendorsByCost,
  useGameStore,
} from "@/lib/game/store";

export default function GameApp() {
  const [bootstrapLoaded, setBootstrapLoaded] = useState(false);

  const status = useGameStore((state) => state.status);
  const error = useGameStore((state) => state.error);
  const bootstrap = useGameStore((state) => state.bootstrap);
  const snapshot = useGameStore((state) => state.snapshot);
  const selectedSlotId = useGameStore((state) => state.selectedSlotId);
  const selectedCategorySlug = useGameStore((state) => state.selectedCategorySlug);
  const autosaveState = useGameStore((state) => state.autosaveState);
  const bootSource = useGameStore((state) => state.bootSource);
  const initialize = useGameStore((state) => state.initialize);
  const startNewGame = useGameStore((state) => state.startNewGame);
  const selectSlot = useGameStore((state) => state.selectSlot);
  const selectCategory = useGameStore((state) => state.selectCategory);
  const placeVendor = useGameStore((state) => state.placeVendor);
  const tick = useGameStore((state) => state.tick);
  const setCamera = useGameStore((state) => state.setCamera);
  const markAutosaved = useGameStore((state) => state.markAutosaved);
  const setError = useGameStore((state) => state.setError);

  const lastSavedSnapshotRef = useRef("");
  const snapshotRef = useRef(snapshot);

  useEffect(() => {
    snapshotRef.current = snapshot;
  }, [snapshot]);

  const flushAutosave = useEffectEvent(() => {
    const currentSnapshot = snapshotRef.current;
    if (!currentSnapshot) {
      return;
    }

    const serialized = JSON.stringify(currentSnapshot);
    if (serialized === lastSavedSnapshotRef.current) {
      return;
    }

    localGameStorage.saveLatest(currentSnapshot);
    lastSavedSnapshotRef.current = serialized;
    markAutosaved();
  });

  useEffect(() => {
    let isMounted = true;

    async function loadBootstrap() {
      try {
        const response = await fetch("/api/game/bootstrap");
        if (!response.ok) {
          throw new Error(`Bootstrap request failed with ${response.status}.`);
        }

        const payload = GameBootstrapSchema.parse(await response.json());
        if (!isMounted) {
          return;
        }

        initialize(payload, localGameStorage.loadLatest());
        setBootstrapLoaded(true);
      } catch (bootstrapError) {
        if (!isMounted) {
          return;
        }

        setError(
          bootstrapError instanceof Error
            ? bootstrapError.message
            : "Bootstrap could not be loaded.",
        );
      }
    }

    loadBootstrap();

    return () => {
      isMounted = false;
    };
  }, [initialize, setError]);

  useEffect(() => {
    if (!bootstrapLoaded || status !== "ready") {
      return;
    }

    let animationFrame = 0;
    let lastFrame = performance.now();
    let accumulator = 0;

    const loop = (frameTime: number) => {
      const deltaSeconds = Math.min((frameTime - lastFrame) / 1000, 0.25);
      lastFrame = frameTime;
      accumulator += deltaSeconds;

      while (accumulator >= GAME_FIXED_STEP_SECONDS) {
        tick(GAME_FIXED_STEP_SECONDS);
        accumulator -= GAME_FIXED_STEP_SECONDS;
      }

      animationFrame = window.requestAnimationFrame(loop);
    };

    animationFrame = window.requestAnimationFrame(loop);

    return () => {
      window.cancelAnimationFrame(animationFrame);
    };
  }, [bootstrapLoaded, status, tick]);

  useEffect(() => {
    if (autosaveState !== "dirty") {
      return;
    }

    flushAutosave();
  }, [autosaveState]);

  useEffect(() => {
    if (!bootstrap || status !== "ready") {
      return;
    }

    const flushBeforeHide = () => {
      flushAutosave();
    };
    const onVisibilityChange = () => {
      if (document.visibilityState === "hidden") {
        flushAutosave();
      }
    };

    const timer = window.setInterval(flushAutosave, bootstrap.simulationConfig.autosaveIntervalMs);

    window.addEventListener("pagehide", flushBeforeHide);
    document.addEventListener("visibilitychange", onVisibilityChange);

    return () => {
      window.clearInterval(timer);
      window.removeEventListener("pagehide", flushBeforeHide);
      document.removeEventListener("visibilitychange", onVisibilityChange);
      flushBeforeHide();
    };
  }, [bootstrap, status]);

  if (status === "error") {
    return (
      <main className={styles.shell}>
        <section className={`${styles.card} ${styles.errorCard}`}>
          <h1 className={styles.sectionTitle}>Bootstrap Error</h1>
          <p className={styles.emptyState}>{error}</p>
        </section>
      </main>
    );
  }

  if (!bootstrap || !snapshot) {
    return (
      <main className={styles.shell}>
        <section className={`${styles.card} ${styles.loadingCard}`}>
          <div>
            <div className={styles.eyebrow}>Initializing</div>
            <h1 className={styles.title}>Loading market bootstrap...</h1>
          </div>
        </section>
      </main>
    );
  }

  const categoryIndex = getCategoryIndex(bootstrap);
  const selectedSlot = bootstrap.map.slots.find((slot) => slot.id === selectedSlotId) ?? null;
  const selectedPlacement = getPlacedVendor(bootstrap, snapshot, selectedSlotId);
  const vendorOptions = sortVendorsByCost(
    getSelectedVendorOptions(bootstrap, selectedCategorySlug),
  );
  const marketOpen = isMarketOpen(snapshot.stats.gameTimeSeconds, bootstrap.simulationConfig);
  const marketStatusLabel = getMarketStatusLabel(
    snapshot.stats.gameTimeSeconds,
    bootstrap.simulationConfig,
  );

  return (
    <main className={styles.shell} data-testid="game-shell">
      <section className={styles.panel}>
        <article className={`${styles.card} ${styles.brandCard}`}>
          <div className={styles.logoRow}>
            <Image
              alt="Milton Market logo"
              height={54}
              src="/images/milton-market-logo.png"
              width={54}
            />
            <div>
              <div className={styles.eyebrow}>Next.js Proof Of Concept</div>
              <h1 className={styles.title}>Market Operator Tycoon</h1>
            </div>
          </div>
          <p className={styles.muted}>
            Deterministic stall management on the legacy Milton market layout, rebuilt with
            Next.js, TypeScript, and React Three Fiber.
          </p>
        </article>

        <article className={`${styles.card} ${styles.summaryCard}`}>
          <div className={styles.summaryGrid}>
            <div className={styles.statBox}>
              <div className={styles.statLabel}>Market</div>
              <div className={styles.statValue}>{marketStatusLabel}</div>
            </div>
            <div className={styles.statBox}>
              <div className={styles.statLabel}>Clock</div>
              <div className={styles.statValue}>{formatClock(snapshot.stats.gameTimeSeconds)}</div>
            </div>
            <div className={styles.statBox}>
              <div className={styles.statLabel}>Day</div>
              <div className={styles.statValue}>{formatDay(snapshot.stats.gameTimeSeconds)}</div>
            </div>
            <div className={styles.statBox}>
              <div className={styles.statLabel}>Customers</div>
              <div className={styles.statValue}>{snapshot.stats.customerCount}</div>
            </div>
            <div className={styles.statBox}>
              <div className={styles.statLabel}>Balance</div>
              <div className={styles.statValue}>
                ${snapshot.stats.accountBalance.toLocaleString()}
              </div>
            </div>
            <div className={styles.statBox}>
                <div className={styles.statLabel}>Today&apos;s Sales</div>
              <div className={styles.statValue}>
                ${snapshot.stats.salesBalance.toLocaleString()}
              </div>
            </div>
          </div>
          <div className={styles.toolbar}>
            <button
              className={`${styles.button} ${styles.buttonPrimary}`}
              data-testid="start-over"
              onClick={() => {
                localGameStorage.clear();
                lastSavedSnapshotRef.current = "";
                startNewGame();
              }}
              type="button"
            >
              Start Over
            </button>
            <span className={styles.detailBadge} data-testid="boot-source">
              {bootSource === "resume" ? "Resumed latest autosave" : "Fresh market day"}
            </span>
            <span className={styles.detailBadge} data-testid="autosave-state">
              Autosave: {autosaveState === "saved" ? "Current" : "Updating"}
            </span>
          </div>
        </article>

        <article className={`${styles.card} ${styles.summaryCard}`}>
          <h2 className={styles.sectionTitle}>Categories</h2>
          <div className={styles.categoryRow}>
            {bootstrap.categories.map((category) => (
              <button
                key={category.slug}
                className={`${styles.button} ${styles.categoryButton} ${
                  selectedCategorySlug === category.slug ? styles.buttonSelected : ""
                }`}
                data-testid={`category-${category.slug}`}
                onClick={() =>
                  selectCategory(
                    selectedCategorySlug === category.slug ? null : (category.slug as CategorySlug),
                  )
                }
                type="button"
              >
                <span>{category.label}</span>
                <span style={{ color: category.color }}>{category.icon}</span>
              </button>
            ))}
          </div>
          <p className={styles.footerHint}>
            Pick a category, then click an empty slot on the market to compare the three curated
            vendor options for that lane.
          </p>
        </article>
      </section>

      <section className={styles.panel}>
        <article className={`${styles.card} ${styles.canvasCard}`}>
          <div className={styles.canvasWrap} data-testid="game-canvas">
            <GameCanvas
              bootstrap={bootstrap}
              onCameraChange={setCamera}
              onSelectSlot={selectSlot}
              selectedSlotId={selectedSlotId}
              snapshot={snapshot}
            />
          </div>
          <div className={styles.canvasOverlay} />
        </article>
      </section>

      <section className={styles.panel}>
        <article className={`${styles.card} ${styles.detailCard}`}>
          <div className={styles.detailHeader}>
            <div>
              <div className={styles.eyebrow}>Selected Slot</div>
              <h2 className={styles.sectionTitle}>
                {selectedSlot ? selectedSlot.label : "Choose a stall"}
              </h2>
            </div>
            <span
              className={`${styles.statusPill} ${
                marketOpen ? styles.statusOpen : styles.statusClosed
              }`}
            >
              {marketStatusLabel}
            </span>
          </div>

          {selectedPlacement && selectedSlot ? (
            <OccupiedSlotView
              categoryColor={
                categoryIndex.get(selectedPlacement.vendor.categorySlug)?.color ?? "#94a3b8"
              }
              selectedPlacement={selectedPlacement}
              selectedSlot={selectedSlot}
            />
          ) : selectedSlot ? (
            <EmptySlotView
              categoryIndex={categoryIndex}
              onPlaceVendor={placeVendor}
              selectedCategorySlug={selectedCategorySlug}
              selectedSlotLabel={selectedSlot.label}
              snapshot={snapshot}
              vendorOptions={vendorOptions}
            />
          ) : (
            <p className={styles.emptyState}>
              Click a market location to inspect a vendor or build into an empty slot.
            </p>
          )}
        </article>
      </section>
    </main>
  );
}

function EmptySlotView({
  selectedSlotLabel,
  selectedCategorySlug,
  categoryIndex,
  vendorOptions,
  snapshot,
  onPlaceVendor,
}: {
  selectedSlotLabel: string;
  selectedCategorySlug: CategorySlug | null;
  categoryIndex: Map<string, GameBootstrap["categories"][number]>;
  vendorOptions: GameBootstrap["vendors"];
  snapshot: NonNullable<ReturnType<typeof useGameStore.getState>["snapshot"]>;
  onPlaceVendor: (vendorId: string) => void;
}) {
  if (!selectedCategorySlug) {
    return (
      <p className={styles.emptyState}>
        Slot <strong>{selectedSlotLabel}</strong> is currently empty. Choose a category from the
        left column to view the three deterministic vendor templates for this build.
      </p>
    );
  }

  const category = categoryIndex.get(selectedCategorySlug);

  return (
    <>
      <span className={styles.detailBadge} style={{ color: category?.color }}>
        {category?.label ?? "Category"}
      </span>
      <div className={styles.categoryRow}>
        {vendorOptions.map((vendor) => {
          const canAfford = snapshot.stats.accountBalance >= vendor.buildCost;

          return (
            <article className={styles.vendorCard} key={vendor.id}>
              <div className={styles.vendorHeading}>
                <div className={styles.vendorName}>{vendor.name}</div>
                <div className={styles.priceTag}>${vendor.buildCost.toLocaleString()}</div>
              </div>
              <div className={styles.muted}>{vendor.contactName}</div>
              <div className={styles.productList}>
                {vendor.products.map((product) => (
                  <div key={product.id}>
                    {product.name} <span className={styles.muted}>${product.price}</span>
                  </div>
                ))}
              </div>
              <ScoreMeters scores={vendor.scores} />
              <div className={styles.toolbar}>
                <button
                  className={`${styles.button} ${styles.buttonPrimary} ${
                    !canAfford ? styles.buttonDisabled : ""
                  }`}
                  data-testid={`build-${vendor.id}`}
                  disabled={!canAfford}
                  onClick={() => onPlaceVendor(vendor.id)}
                  type="button"
                >
                  {canAfford ? "Build Stall" : "Insufficient Funds"}
                </button>
              </div>
            </article>
          );
        })}
      </div>
    </>
  );
}

function OccupiedSlotView({
  selectedPlacement,
  selectedSlot,
  categoryColor,
}: {
  selectedPlacement: NonNullable<ReturnType<typeof getPlacedVendor>>;
  selectedSlot: GameBootstrap["map"]["slots"][number];
  categoryColor: string;
}) {
  const { placedStall, vendor } = selectedPlacement;

  return (
    <>
      <span className={styles.detailBadge} style={{ color: categoryColor }}>
        {vendor.categorySlug}
      </span>
      <div className={styles.vendorHeading}>
        <div>
          <div className={styles.vendorName}>{vendor.name}</div>
          <div className={styles.muted}>{vendor.contactName}</div>
        </div>
        <div className={styles.priceTag}>{selectedSlot.label}</div>
      </div>
      <div className={styles.productList}>
        {vendor.products.map((product) => (
          <div key={product.id}>
            {product.name} <span className={styles.muted}>${product.price}</span>
          </div>
        ))}
      </div>
      <ScoreMeters scores={vendor.scores} />
      <div className={styles.summaryGrid}>
        <div className={styles.statBox}>
          <div className={styles.statLabel}>Customers Served</div>
          <div className={styles.statValue}>{placedStall.customerCount}</div>
        </div>
        <div className={styles.statBox}>
          <div className={styles.statLabel}>Revenue</div>
          <div className={styles.statValue}>${placedStall.salesTotal.toLocaleString()}</div>
        </div>
      </div>
    </>
  );
}

function ScoreMeters({
  scores,
}: {
  scores: GameBootstrap["vendors"][number]["scores"];
}) {
  return (
    <div className={styles.metricGrid}>
      <ScoreMeter color="#60a5fa" label="Satisfaction" value={scores.customerSatisfaction} />
      <ScoreMeter color="#34d399" label="Margin" value={scores.productMargin} />
      <ScoreMeter color="#fbbf24" label="Running Costs" value={scores.runningCosts} />
      <ScoreMeter color="#f472b6" label="Appeal" value={scores.customerAppeal} />
    </div>
  );
}

function ScoreMeter({
  label,
  value,
  color,
}: {
  label: string;
  value: number;
  color: string;
}) {
  return (
    <div className={styles.metricRow}>
      <span className={styles.muted}>
        {label} {value}
      </span>
      <div className={styles.meter}>
        <div
          className={styles.meterFill}
          style={{ background: color, width: `${value}%` }}
        />
      </div>
    </div>
  );
}
