<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Inventory;
use App\Models\Recipe;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with('recipes.inventory')->orderBy('name')->get();
        return view('menus.index', compact('menus'));
    }

    public function create()
    {
        $inventories = Inventory::orderBy('name')->get();
        return view('menus.create', compact('inventories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'size' => 'required|in:Medium,Large',
            'price' => 'required|numeric|min:0',
            'ingredients' => 'nullable|array',
            'ingredients.*.inventory_id' => 'required|exists:inventories,id',
            'ingredients.*.quantity' => 'required|numeric|min:0.01',
        ]);

        $menu = Menu::create([
            'name' => $request->name,
            'size' => $request->size,
            'price' => $request->price,
        ]);

        if ($request->has('ingredients')) {
            foreach ($request->ingredients as $ing) {
                Recipe::create([
                    'menu_id' => $menu->id,
                    'inventory_id' => $ing['inventory_id'],
                    'quantity' => $ing['quantity'],
                ]);
            }
        }

        return redirect()->route('menus.index')->with('success', 'Menu berhasil ditambahkan!');
    }

    public function edit(Menu $menu)
    {
        $menu->load('recipes');
        $inventories = Inventory::orderBy('name')->get();
        return view('menus.edit', compact('menu', 'inventories'));
    }

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'size' => 'required|in:Medium,Large',
            'price' => 'required|numeric|min:0',
            'ingredients' => 'nullable|array',
            'ingredients.*.inventory_id' => 'required|exists:inventories,id',
            'ingredients.*.quantity' => 'required|numeric|min:0.01',
        ]);

        $menu->update([
            'name' => $request->name,
            'size' => $request->size,
            'price' => $request->price,
        ]);

        // Recreate recipes
        $menu->recipes()->delete();
        if ($request->has('ingredients')) {
            foreach ($request->ingredients as $ing) {
                Recipe::create([
                    'menu_id' => $menu->id,
                    'inventory_id' => $ing['inventory_id'],
                    'quantity' => $ing['quantity'],
                ]);
            }
        }

        return redirect()->route('menus.index')->with('success', 'Menu berhasil diperbarui!');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return redirect()->route('menus.index')->with('success', 'Menu berhasil dihapus!');
    }
}
