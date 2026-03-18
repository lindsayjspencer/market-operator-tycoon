import type { CategorySeed } from "@/lib/game/schemas";

export const categories: CategorySeed[] = [
  {
    slug: "food",
    label: "Food & Drinks",
    icon: "utensils",
    color: "#f07f2f",
  },
  {
    slug: "produce",
    label: "Fruit & Vegetables",
    icon: "leaf",
    color: "#3da35d",
  },
  {
    slug: "handmade",
    label: "Handmade",
    icon: "palette",
    color: "#c15f7b",
  },
  {
    slug: "provisions",
    label: "Provisions",
    icon: "basket",
    color: "#c89b34",
  },
];
