"use client";

import { Canvas, useFrame, useThree } from "@react-three/fiber";
import { OrbitControls, Sky, useTexture } from "@react-three/drei";
import { useEffect, useMemo, useRef } from "react";
import * as THREE from "three";
import type { OrbitControls as OrbitControlsImpl } from "three-stdlib";
import type { CameraState, GameBootstrap, GameSnapshot } from "@/lib/game/schemas";
import { getVendorIndex } from "@/lib/game/store";

const STALL_FLOOR_SIZE = 7.05;
const STALL_FLOOR_THICKNESS = 0.18;
const STALL_POST_HEIGHT = 4.3;
const STALL_POST_WIDTH = 0.18;
const STALL_POST_OFFSET = 3.1;
const STALL_COUNTER_HEIGHT = 1.45;
const STALL_COUNTER_THICKNESS = 0.2;
const STALL_LONG_COUNTER = 5.9;
const STALL_SHORT_COUNTER = 1.55;
const STALL_ROOF_BOX_SIZE = 6.55;
const STALL_ROOF_BOX_HEIGHT = 0.58;
const STALL_ROOF_BOX_Y = 4.12;
const STALL_TENT_Y = 4.95;

interface GameCanvasProps {
  bootstrap: GameBootstrap;
  snapshot: GameSnapshot;
  selectedSlotId: number | null;
  onSelectSlot: (slotId: number) => void;
  onCameraChange: (camera: CameraState) => void;
}

interface StallAssets {
  canopyTextures: THREE.Texture[];
  chalkboardTextures: THREE.Texture[];
  woodTexture: THREE.Texture;
}

interface TableLayout {
  position: [number, number, number];
  size: [number, number, number];
  rotation?: [number, number, number];
}

export default function GameCanvas({
  bootstrap,
  snapshot,
  selectedSlotId,
  onSelectSlot,
  onCameraChange,
}: GameCanvasProps) {
  return (
    <Canvas
      camera={{
        position: [
          snapshot.camera.position.x,
          snapshot.camera.position.y,
          snapshot.camera.position.z,
        ],
        fov: 42,
      }}
      shadows
    >
      <color attach="background" args={["#9ec8e0"]} />
      <fog attach="fog" args={["#9ec8e0", 90, 240]} />
      <Sky distance={450000} sunPosition={[1, 0.5, 1]} inclination={0.48} />
      <ambientLight intensity={0.55} />
      <directionalLight
        castShadow
        intensity={2.1}
        position={[35, 80, 24]}
        shadow-mapSize-width={2048}
        shadow-mapSize-height={2048}
      />
      <Ground bootstrap={bootstrap} />
      <SlotMarkers
        bootstrap={bootstrap}
        snapshot={snapshot}
        selectedSlotId={selectedSlotId}
        onSelectSlot={onSelectSlot}
      />
      <CameraRig cameraState={snapshot.camera} onCameraChange={onCameraChange} />
    </Canvas>
  );
}

function Ground({ bootstrap }: { bootstrap: GameBootstrap }) {
  const width = bootstrap.map.bounds.maxX - bootstrap.map.bounds.minX + 34;
  const depth = bootstrap.map.bounds.maxY - bootstrap.map.bounds.minY + 34;
  const centerX = (bootstrap.map.bounds.maxX + bootstrap.map.bounds.minX) / 2;
  const centerY = (bootstrap.map.bounds.maxY + bootstrap.map.bounds.minY) / 2;

  return (
    <group>
      <mesh
        receiveShadow
        rotation={[-Math.PI / 2, 0, 0]}
        position={[centerX, -0.8, centerY]}
      >
        <planeGeometry args={[width, depth]} />
        <meshStandardMaterial color="#6f9b5e" roughness={1} />
      </mesh>
      <gridHelper
        args={[Math.max(width, depth), 24, "#8fb3c0", "#4b6b78"]}
        position={[centerX, -0.72, centerY]}
      />
    </group>
  );
}

function SlotMarkers({
  bootstrap,
  snapshot,
  selectedSlotId,
  onSelectSlot,
}: {
  bootstrap: GameBootstrap;
  snapshot: GameSnapshot;
  selectedSlotId: number | null;
  onSelectSlot: (slotId: number) => void;
}) {
  const vendorIndex = getVendorIndex(bootstrap);
  const stallAssets = useStallAssets();

  return (
    <group>
      {bootstrap.map.slots.map((slot) => {
        const placedStall = snapshot.placedStalls.find((entry) => entry.slotId === slot.id);
        const vendor = placedStall ? vendorIndex.get(placedStall.vendorId) ?? null : null;
        const category = vendor
          ? bootstrap.categories.find((entry) => entry.slug === vendor.categorySlug)
          : null;

        return (
          <SlotMarker
            categoryColor={category?.color ?? "#8da2b8"}
            isFilled={Boolean(placedStall)}
            isSelected={selectedSlotId === slot.id}
            key={slot.id}
            onSelectSlot={onSelectSlot}
            slot={slot}
            stallAssets={stallAssets}
            vendor={vendor}
          />
        );
      })}
    </group>
  );
}

function SlotMarker({
  slot,
  vendor,
  categoryColor,
  isSelected,
  isFilled,
  stallAssets,
  onSelectSlot,
}: {
  slot: GameBootstrap["map"]["slots"][number];
  vendor: GameBootstrap["vendors"][number] | null;
  categoryColor: string;
  isSelected: boolean;
  isFilled: boolean;
  stallAssets: StallAssets;
  onSelectSlot: (slotId: number) => void;
}) {
  const selectionRef = useRef<THREE.Mesh>(null);
  const canopyIndex = hashSeed(vendor?.id ?? slot.label) % stallAssets.canopyTextures.length;
  const boardIndex = (hashSeed(slot.label) + slot.id) % stallAssets.chalkboardTextures.length;
  const layoutVariant = (hashSeed(vendor?.id ?? `${slot.id}`) + slot.id) % 4;

  useFrame(({ clock }) => {
    if (!selectionRef.current) {
      return;
    }

    const pulse = isSelected ? 1.02 + Math.sin(clock.elapsedTime * 4) * 0.02 : 1;
    selectionRef.current.scale.set(pulse, pulse, pulse);
    selectionRef.current.visible = isSelected;
  });

  return (
    <group
      position={[slot.x, 0, slot.y]}
      rotation={[0, THREE.MathUtils.degToRad(-slot.rotation), 0]}
      onClick={(event) => {
        event.stopPropagation();
        onSelectSlot(slot.id);
      }}
      onPointerOut={() => {
        document.body.style.cursor = "default";
      }}
      onPointerOver={(event) => {
        event.stopPropagation();
        document.body.style.cursor = "pointer";
      }}
    >
      <StallPad
        categoryColor={categoryColor}
        isFilled={isFilled}
        isSelected={isSelected}
      />

      {isFilled && vendor ? (
        <DetailedStall
          boardTexture={stallAssets.chalkboardTextures[boardIndex]}
          canopyTexture={stallAssets.canopyTextures[canopyIndex]}
          categoryColor={categoryColor}
          hasAwning={slot.hasAwning}
          layoutVariant={layoutVariant}
          vendor={vendor}
          woodTexture={stallAssets.woodTexture}
        />
      ) : (
        <EmptySlotGhost isSelected={isSelected} />
      )}

      <mesh
        ref={selectionRef}
        position={[0, isFilled ? 2.8 : 0.56, 0]}
        visible={isSelected}
      >
        <boxGeometry args={[7.85, isFilled ? 5.4 : 0.95, 7.85]} />
        <meshBasicMaterial color={isFilled ? "#f8fafc" : "#d9f99d"} wireframe />
      </mesh>
    </group>
  );
}

function StallPad({
  isFilled,
  isSelected,
  categoryColor,
}: {
  isFilled: boolean;
  isSelected: boolean;
  categoryColor: string;
}) {
  return (
    <group>
      <mesh castShadow position={[0, 0.02, 0]} receiveShadow>
        <boxGeometry args={[STALL_FLOOR_SIZE + 0.32, 0.05, STALL_FLOOR_SIZE + 0.32]} />
        <meshStandardMaterial color="#2a3d4f" roughness={0.96} />
      </mesh>
      <mesh castShadow position={[0, 0.12, 0]} receiveShadow>
        <boxGeometry args={[STALL_FLOOR_SIZE, STALL_FLOOR_THICKNESS, STALL_FLOOR_SIZE]} />
        <meshStandardMaterial
          color={isFilled ? "#738290" : "#5698ff"}
          metalness={0.02}
          roughness={0.75}
        />
      </mesh>
      {isFilled ? (
        <mesh position={[0, 0.23, 0]}>
          <boxGeometry args={[STALL_FLOOR_SIZE - 0.65, 0.05, STALL_FLOOR_SIZE - 0.65]} />
          <meshStandardMaterial color={categoryColor} roughness={0.5} />
        </mesh>
      ) : null}
      {isSelected && !isFilled ? (
        <mesh position={[0, 0.24, 0]}>
          <boxGeometry args={[STALL_FLOOR_SIZE - 0.95, 0.06, STALL_FLOOR_SIZE - 0.95]} />
          <meshStandardMaterial color="#d9f99d" emissive="#d9f99d" emissiveIntensity={0.2} />
        </mesh>
      ) : null}
    </group>
  );
}

function EmptySlotGhost({ isSelected }: { isSelected: boolean }) {
  return (
    <group position={[0, 0.28, 0]}>
      <GhostPost position={[-3.02, 0.38, -3.02]} selected={isSelected} />
      <GhostPost position={[-3.02, 0.38, 3.02]} selected={isSelected} />
      <GhostPost position={[3.02, 0.38, -3.02]} selected={isSelected} />
      <GhostPost position={[3.02, 0.38, 3.02]} selected={isSelected} />
      <mesh castShadow position={[0, 0.8, 0]} receiveShadow>
        <boxGeometry args={[6.4, 0.12, 6.4]} />
        <meshStandardMaterial
          color={isSelected ? "#d9f99d" : "#dbe7f5"}
          opacity={isSelected ? 0.82 : 0.42}
          roughness={0.62}
          transparent
        />
      </mesh>
      <mesh position={[0, 1.15, 0]} rotation={[0, Math.PI / 4, 0]}>
        <cylinderGeometry args={[0.42, 3.35, 0.72, 4]} />
        <meshStandardMaterial
          color={isSelected ? "#eef8c2" : "#c7d6e6"}
          opacity={isSelected ? 0.5 : 0.22}
          roughness={0.74}
          side={THREE.DoubleSide}
          transparent
        />
      </mesh>
    </group>
  );
}

function GhostPost({
  position,
  selected,
}: {
  position: [number, number, number];
  selected: boolean;
}) {
  return (
    <mesh position={position}>
      <boxGeometry args={[0.16, 0.76, 0.16]} />
      <meshStandardMaterial
        color={selected ? "#f8fafc" : "#d4e1f0"}
        opacity={selected ? 1 : 0.56}
        roughness={0.45}
        transparent
      />
    </mesh>
  );
}

function DetailedStall({
  canopyTexture,
  boardTexture,
  woodTexture,
  vendor,
  categoryColor,
  layoutVariant,
  hasAwning,
}: {
  canopyTexture: THREE.Texture;
  boardTexture: THREE.Texture;
  woodTexture: THREE.Texture;
  vendor: GameBootstrap["vendors"][number];
  categoryColor: string;
  layoutVariant: number;
  hasAwning: boolean;
}) {
  const tableLayouts = getTableLayouts(layoutVariant);

  return (
    <group>
      <mesh castShadow position={[0, STALL_ROOF_BOX_Y, 0]} receiveShadow>
        <boxGeometry args={[STALL_ROOF_BOX_SIZE, STALL_ROOF_BOX_HEIGHT, STALL_ROOF_BOX_SIZE]} />
        <meshStandardMaterial
          color={new THREE.Color(categoryColor).multiplyScalar(0.92)}
          map={canopyTexture}
          roughness={0.72}
        />
      </mesh>
      <mesh castShadow position={[0, STALL_TENT_Y, 0]} receiveShadow rotation={[0, Math.PI / 4, 0]}>
        <cylinderGeometry args={[0.76, 4.45, 2.28, 4]} />
        <meshStandardMaterial
          color={new THREE.Color(categoryColor).multiplyScalar(1.12)}
          map={canopyTexture}
          roughness={0.64}
          side={THREE.DoubleSide}
        />
      </mesh>

      <PerimeterBeam position={[0, 3.9, -STALL_POST_OFFSET]} rotation={[0, 0, Math.PI / 2]} />
      <PerimeterBeam position={[0, 3.9, STALL_POST_OFFSET]} rotation={[0, 0, Math.PI / 2]} />
      <PerimeterBeam position={[-STALL_POST_OFFSET, 3.9, 0]} rotation={[Math.PI / 2, 0, 0]} />
      <PerimeterBeam position={[STALL_POST_OFFSET, 3.9, 0]} rotation={[Math.PI / 2, 0, 0]} />

      <Post position={[-STALL_POST_OFFSET, 2.15, -STALL_POST_OFFSET]} />
      <Post position={[-STALL_POST_OFFSET, 2.15, STALL_POST_OFFSET]} />
      <Post position={[STALL_POST_OFFSET, 2.15, -STALL_POST_OFFSET]} />
      <Post position={[STALL_POST_OFFSET, 2.15, STALL_POST_OFFSET]} />

      {tableLayouts.map((table, index) => (
        <StallTable
          categoryColor={categoryColor}
          key={`${vendor.id}-${index}`}
          position={table.position}
          rotation={table.rotation}
          size={table.size}
          topTexture={woodTexture}
        >
          <DisplayStock categorySlug={vendor.categorySlug} size={table.size} />
        </StallTable>
      ))}

      {layoutVariant === 3 ? <Chalkboard boardTexture={boardTexture} /> : null}
      {hasAwning ? <SideAwning canopyTexture={canopyTexture} tint={categoryColor} /> : null}
    </group>
  );
}

function Post({ position }: { position: [number, number, number] }) {
  return (
    <mesh castShadow position={position} receiveShadow>
      <boxGeometry args={[STALL_POST_WIDTH, STALL_POST_HEIGHT, STALL_POST_WIDTH]} />
      <meshStandardMaterial color="#f2f5f9" metalness={0.08} roughness={0.46} />
    </mesh>
  );
}

function PerimeterBeam({
  position,
  rotation,
}: {
  position: [number, number, number];
  rotation: [number, number, number];
}) {
  return (
    <mesh castShadow position={position} receiveShadow rotation={rotation}>
      <cylinderGeometry args={[0.1, 0.1, 6.2, 10]} />
      <meshStandardMaterial color="#f2f5f9" metalness={0.08} roughness={0.46} />
    </mesh>
  );
}

function StallTable({
  position,
  rotation,
  size,
  topTexture,
  categoryColor,
  children,
}: {
  position: [number, number, number];
  rotation?: [number, number, number];
  size: [number, number, number];
  topTexture: THREE.Texture;
  categoryColor: string;
  children?: React.ReactNode;
}) {
  return (
    <group position={position} rotation={rotation}>
      <mesh castShadow position={[0, 0, 0]} receiveShadow>
        <boxGeometry args={size} />
        <meshStandardMaterial
          color={new THREE.Color(categoryColor).multiplyScalar(0.74)}
          roughness={0.88}
        />
      </mesh>
      <mesh castShadow position={[0, size[1] / 2 + STALL_COUNTER_THICKNESS / 2, 0]} receiveShadow>
        <boxGeometry args={[size[0], STALL_COUNTER_THICKNESS, size[2]]} />
        <meshStandardMaterial map={topTexture} roughness={0.84} />
      </mesh>
      <group position={[0, size[1] / 2 + STALL_COUNTER_THICKNESS + 0.05, 0]}>{children}</group>
    </group>
  );
}

function DisplayStock({
  categorySlug,
  size,
}: {
  categorySlug: GameBootstrap["vendors"][number]["categorySlug"];
  size: [number, number, number];
}) {
  const stockColor =
    categorySlug === "food"
      ? "#f29d52"
      : categorySlug === "produce"
        ? "#7bc96f"
        : categorySlug === "handmade"
          ? "#d68bab"
          : "#d6b05c";

  return (
    <group>
      <mesh castShadow position={[-size[0] * 0.2, 0.16, -size[2] * 0.2]} receiveShadow>
        <boxGeometry args={[0.5, 0.32, 0.5]} />
        <meshStandardMaterial color={stockColor} roughness={0.6} />
      </mesh>
      <mesh castShadow position={[0.1, 0.18, 0.1]} receiveShadow>
        {categorySlug === "produce" ? (
          <sphereGeometry args={[0.28, 16, 16]} />
        ) : (
          <cylinderGeometry args={[0.18, 0.18, 0.36, 16]} />
        )}
        <meshStandardMaterial color={stockColor} roughness={0.42} />
      </mesh>
      <mesh castShadow position={[size[0] * 0.22, 0.14, -0.08]} receiveShadow>
        <boxGeometry args={[0.42, 0.26, 0.42]} />
        <meshStandardMaterial color="#f8f8f4" roughness={0.5} />
      </mesh>
    </group>
  );
}

function Chalkboard({ boardTexture }: { boardTexture: THREE.Texture }) {
  return (
    <group position={[-3.4, 1.55, 2.15]}>
      <mesh castShadow position={[0, 0.75, 0]} receiveShadow>
        <boxGeometry args={[0.16, 2.2, 1.45]} />
        <meshStandardMaterial map={boardTexture} roughness={0.9} />
      </mesh>
      <mesh castShadow position={[0, -0.2, -0.45]} rotation={[0.18, 0, 0]} receiveShadow>
        <boxGeometry args={[0.12, 2.3, 0.12]} />
        <meshStandardMaterial color="#7b5635" roughness={0.86} />
      </mesh>
      <mesh castShadow position={[0, -0.2, 0.45]} rotation={[-0.18, 0, 0]} receiveShadow>
        <boxGeometry args={[0.12, 2.3, 0.12]} />
        <meshStandardMaterial color="#7b5635" roughness={0.86} />
      </mesh>
    </group>
  );
}

function SideAwning({
  canopyTexture,
  tint,
}: {
  canopyTexture: THREE.Texture;
  tint: string;
}) {
  return (
    <group position={[4.45, 0.25, 0]}>
      <mesh castShadow position={[0, 3.1, 0]} receiveShadow rotation={[0, 0, -0.16]}>
        <boxGeometry args={[1.6, 0.12, 5.9]} />
        <meshStandardMaterial
          color={new THREE.Color(tint).multiplyScalar(1.04)}
          map={canopyTexture}
          roughness={0.66}
        />
      </mesh>
      <mesh castShadow position={[0.72, 1.8, -2.5]} receiveShadow rotation={[0.08, 0, 0]}>
        <boxGeometry args={[0.12, 3.2, 0.12]} />
        <meshStandardMaterial color="#f2f5f9" roughness={0.45} />
      </mesh>
      <mesh castShadow position={[0.72, 1.8, 2.5]} receiveShadow rotation={[-0.08, 0, 0]}>
        <boxGeometry args={[0.12, 3.2, 0.12]} />
        <meshStandardMaterial color="#f2f5f9" roughness={0.45} />
      </mesh>
    </group>
  );
}

function CameraRig({
  cameraState,
  onCameraChange,
}: {
  cameraState: CameraState;
  onCameraChange: (camera: CameraState) => void;
}) {
  const controlsRef = useRef<OrbitControlsImpl | null>(null);
  const { camera } = useThree();

  useEffect(() => {
    camera.position.set(
      cameraState.position.x,
      cameraState.position.y,
      cameraState.position.z,
    );

    if (controlsRef.current) {
      controlsRef.current.target.set(
        cameraState.target.x,
        cameraState.target.y,
        cameraState.target.z,
      );
      controlsRef.current.update();
    }
  }, [camera, cameraState]);

  return (
    <OrbitControls
      ref={controlsRef}
      makeDefault
      enablePan
      maxPolarAngle={Math.PI / 2.12}
      maxDistance={180}
      minDistance={22}
      onEnd={() => {
        if (!controlsRef.current) {
          return;
        }

        onCameraChange({
          position: {
            x: camera.position.x,
            y: camera.position.y,
            z: camera.position.z,
          },
          target: {
            x: controlsRef.current.target.x,
            y: controlsRef.current.target.y,
            z: controlsRef.current.target.z,
          },
        });
      }}
    />
  );
}

function useStallAssets(): StallAssets {
  const { gl } = useThree();
  const rawCanopyTextures = useTexture([
    "/textures/stalls/rsz_fabric0-min.png",
    "/textures/stalls/rsz_fabric1-min.png",
    "/textures/stalls/rsz_fabric2-min.jpg",
    "/textures/stalls/rsz_fabric3-min.jpg",
    "/textures/stalls/rsz_fabric4-min.jpg",
  ]) as THREE.Texture[];
  const rawChalkboardTextures = useTexture([
    "/textures/stalls/chalkboards/chalkboard0-min.jpg",
    "/textures/stalls/chalkboards/chalkboard1-min.jpg",
    "/textures/stalls/chalkboards/chalkboard2-min.jpg",
    "/textures/stalls/chalkboards/chalkboard3-min.jpg",
    "/textures/stalls/chalkboards/chalkboard4-min.jpg",
    "/textures/stalls/chalkboards/chalkboard5-min.jpg",
    "/textures/stalls/chalkboards/chalkboard6-min.jpg",
    "/textures/stalls/chalkboards/chalkboard7-min.jpg",
  ]) as THREE.Texture[];
  const rawWoodTexture = useTexture("/textures/stalls/rsz_wood2-min.jpg") as THREE.Texture;

  const anisotropy = gl.capabilities.getMaxAnisotropy();
  const canopyTextures = useMemo(
    () =>
      rawCanopyTextures.map((texture) => {
        const clone = texture.clone();
        clone.wrapS = THREE.RepeatWrapping;
        clone.wrapT = THREE.RepeatWrapping;
        clone.repeat.set(3.5, 1.75);
        clone.anisotropy = anisotropy;
        clone.colorSpace = THREE.SRGBColorSpace;
        clone.needsUpdate = true;
        return clone;
      }),
    [anisotropy, rawCanopyTextures],
  );
  const chalkboardTextures = useMemo(
    () =>
      rawChalkboardTextures.map((texture) => {
        const clone = texture.clone();
        clone.anisotropy = anisotropy;
        clone.colorSpace = THREE.SRGBColorSpace;
        clone.needsUpdate = true;
        return clone;
      }),
    [anisotropy, rawChalkboardTextures],
  );
  const woodTexture = useMemo(() => {
    const clone = rawWoodTexture.clone();
    clone.wrapS = THREE.RepeatWrapping;
    clone.wrapT = THREE.RepeatWrapping;
    clone.repeat.set(1.35, 1.35);
    clone.anisotropy = anisotropy;
    clone.colorSpace = THREE.SRGBColorSpace;
    clone.needsUpdate = true;
    return clone;
  }, [anisotropy, rawWoodTexture]);

  return {
    canopyTextures,
    chalkboardTextures,
    woodTexture,
  };
}

function getTableLayouts(variant: number): TableLayout[] {
  switch (variant) {
    case 0:
      return [
        {
          position: [-2.35, STALL_COUNTER_HEIGHT / 2, 0] as [number, number, number],
          size: [STALL_SHORT_COUNTER, STALL_COUNTER_HEIGHT, STALL_LONG_COUNTER] as [
            number,
            number,
            number,
          ],
        },
      ];
    case 1:
      return [
        {
          position: [2.35, STALL_COUNTER_HEIGHT / 2, 0] as [number, number, number],
          size: [STALL_SHORT_COUNTER, STALL_COUNTER_HEIGHT, STALL_LONG_COUNTER] as [
            number,
            number,
            number,
          ],
        },
        {
          position: [-0.78, STALL_COUNTER_HEIGHT / 2, -2.2] as [number, number, number],
          size: [4.4, STALL_COUNTER_HEIGHT, STALL_SHORT_COUNTER] as [number, number, number],
        },
        {
          position: [-0.78, STALL_COUNTER_HEIGHT / 2, 2.2] as [number, number, number],
          size: [4.4, STALL_COUNTER_HEIGHT, STALL_SHORT_COUNTER] as [number, number, number],
        },
      ];
    case 2:
      return [
        {
          position: [2.35, STALL_COUNTER_HEIGHT / 2, 0] as [number, number, number],
          size: [STALL_SHORT_COUNTER, STALL_COUNTER_HEIGHT, STALL_LONG_COUNTER] as [
            number,
            number,
            number,
          ],
        },
        {
          position: [-0.78, STALL_COUNTER_HEIGHT / 2, -2.2] as [number, number, number],
          size: [4.4, STALL_COUNTER_HEIGHT, STALL_SHORT_COUNTER] as [number, number, number],
        },
      ];
    default:
      return [
        {
          position: [-2.35, STALL_COUNTER_HEIGHT / 2, 0] as [number, number, number],
          size: [STALL_SHORT_COUNTER, STALL_COUNTER_HEIGHT, STALL_LONG_COUNTER] as [
            number,
            number,
            number,
          ],
        },
      ];
  }
}

function hashSeed(value: string) {
  let hash = 0;

  for (let index = 0; index < value.length; index += 1) {
    hash = (hash << 5) - hash + value.charCodeAt(index);
    hash |= 0;
  }

  return Math.abs(hash);
}
