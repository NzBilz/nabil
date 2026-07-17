<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Inventory;
use App\Models\Recipe;
use Illuminate\Database\Seeder;

class RecipeSeeder extends Seeder
{
    public function run(): void
    {
        // Get all required models
        $cupM = Inventory::where('name', 'Cup Medium')->first();
        $cupL = Inventory::where('name', 'Cup Large')->first();
        $straw = Inventory::where('name', 'Sedotan')->first();
        $teaOrig = Inventory::where('name', 'Bubuk Teh Original')->first();
        $teaJasm = Inventory::where('name', 'Bubuk Teh Jasmine')->first();
        $teaMilk = Inventory::where('name', 'Bubuk Teh Milk Tea')->first();
        $sugar = Inventory::where('name', 'Gula Cair')->first();
        $ice = Inventory::where('name', 'Es Batu')->first();

        // 1. Original Medium
        $menuOrigM = Menu::where(['name' => 'Original', 'size' => 'Medium'])->first();
        if ($menuOrigM) {
            $this->addRecipe($menuOrigM->id, $cupM->id, 1);
            $this->addRecipe($menuOrigM->id, $straw->id, 1);
            $this->addRecipe($menuOrigM->id, $teaOrig->id, 15);
            $this->addRecipe($menuOrigM->id, $sugar->id, 30);
            $this->addRecipe($menuOrigM->id, $ice->id, 15);
        }

        // 2. Original Large
        $menuOrigL = Menu::where(['name' => 'Original', 'size' => 'Large'])->first();
        if ($menuOrigL) {
            $this->addRecipe($menuOrigL->id, $cupL->id, 1);
            $this->addRecipe($menuOrigL->id, $straw->id, 1);
            $this->addRecipe($menuOrigL->id, $teaOrig->id, 25);
            $this->addRecipe($menuOrigL->id, $sugar->id, 45);
            $this->addRecipe($menuOrigL->id, $ice->id, 25);
        }

        // 3. Jasmine Medium
        $menuJasmM = Menu::where(['name' => 'Jasmine', 'size' => 'Medium'])->first();
        if ($menuJasmM) {
            $this->addRecipe($menuJasmM->id, $cupM->id, 1);
            $this->addRecipe($menuJasmM->id, $straw->id, 1);
            $this->addRecipe($menuJasmM->id, $teaJasm->id, 15);
            $this->addRecipe($menuJasmM->id, $sugar->id, 30);
            $this->addRecipe($menuJasmM->id, $ice->id, 15);
        }

        // 4. Jasmine Large
        $menuJasmL = Menu::where(['name' => 'Jasmine', 'size' => 'Large'])->first();
        if ($menuJasmL) {
            $this->addRecipe($menuJasmL->id, $cupL->id, 1);
            $this->addRecipe($menuJasmL->id, $straw->id, 1);
            $this->addRecipe($menuJasmL->id, $teaJasm->id, 25);
            $this->addRecipe($menuJasmL->id, $sugar->id, 45);
            $this->addRecipe($menuJasmL->id, $ice->id, 25);
        }

        // 5. Milk Tea Medium
        $menuMilkM = Menu::where(['name' => 'Milk Tea', 'size' => 'Medium'])->first();
        if ($menuMilkM) {
            $this->addRecipe($menuMilkM->id, $cupM->id, 1);
            $this->addRecipe($menuMilkM->id, $straw->id, 1);
            $this->addRecipe($menuMilkM->id, $teaMilk->id, 20);
            $this->addRecipe($menuMilkM->id, $sugar->id, 25);
            $this->addRecipe($menuMilkM->id, $ice->id, 15);
        }

        // 6. Milk Tea Large
        $menuMilkL = Menu::where(['name' => 'Milk Tea', 'size' => 'Large'])->first();
        if ($menuMilkL) {
            $this->addRecipe($menuMilkL->id, $cupL->id, 1);
            $this->addRecipe($menuMilkL->id, $straw->id, 1);
            $this->addRecipe($menuMilkL->id, $teaMilk->id, 30);
            $this->addRecipe($menuMilkL->id, $sugar->id, 40);
            $this->addRecipe($menuMilkL->id, $ice->id, 25);
        }
    }

    private function addRecipe($menuId, $inventoryId, $quantity): void
    {
        Recipe::updateOrCreate(
            ['menu_id' => $menuId, 'inventory_id' => $inventoryId],
            ['quantity' => $quantity]
        );
    }
}
