<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Inventory;
use App\Models\Recipe;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    /**
     * Display a listing of menus and their recipes.
     */
    public function index()
    {
        $menus = Menu::with('recipes.inventory')->orderBy('name')->get();
        return view('recipes.index', compact('menus'));
    }

    /**
     * Show the form for editing the recipe of a specific menu.
     */
    public function edit(Menu $menu)
    {
        $menu->load('recipes.inventory');
        $inventories = Inventory::orderBy('name')->get();
        return view('recipes.edit', compact('menu', 'inventories'));
    }

    /**
     * Update the recipe for a specific menu.
     */
    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'ingredients' => 'nullable|array',
            'ingredients.*.inventory_id' => 'required|exists:inventories,id',
            'ingredients.*.quantity' => 'nullable|numeric|min:0',
        ]);

        // Delete existing recipes for this menu
        $menu->recipes()->delete();

        // Save new recipes
        if ($request->has('ingredients')) {
            foreach ($request->ingredients as $ing) {
                $qty = isset($ing['quantity']) ? floatval($ing['quantity']) : 0;
                if ($qty > 0) {
                    Recipe::create([
                        'menu_id' => $menu->id,
                        'inventory_id' => $ing['inventory_id'],
                        'quantity' => $qty,
                    ]);
                }
            }
        }

        return redirect()->route('recipes.index')->with('success', 'Resep untuk menu "' . $menu->name . ' (' . $menu->size . ')" berhasil diperbarui!');
    }
}
