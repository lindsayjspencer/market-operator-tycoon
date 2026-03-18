import { expect, test } from "@playwright/test";

test("loads the game, builds a stall, and resumes the autosave after refresh", async ({
  page,
}) => {
  await page.goto("/");

  await expect(
    page.getByRole("heading", { name: "Market Operator Tycoon" }),
  ).toBeVisible();
  await expect(page.getByAltText("Milton Market logo")).toBeVisible();
  await expect(page.getByTestId("game-canvas").locator("canvas")).toBeVisible();
  await expect(page.getByTestId("boot-source")).toHaveText("Fresh market day");

  await page.getByTestId("category-food").click();
  await page.getByTestId("build-food-master-cham").click();

  await expect(page.getByText("Master Cham")).toBeVisible();
  await expect(page.getByText("Customers Served")).toBeVisible();
  await expect(page.getByTestId("autosave-state")).toHaveText("Autosave: Current");

  await page.reload();

  await expect(page.getByTestId("boot-source")).toHaveText("Resumed latest autosave");
  await expect(page.getByText("Master Cham")).toBeVisible();

  await page.getByTestId("start-over").click();

  await expect(page.getByTestId("boot-source")).toHaveText("Fresh market day");
  await expect(page.getByText("Master Cham")).not.toBeVisible();
});
