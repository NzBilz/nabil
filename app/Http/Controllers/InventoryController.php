<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        $inventories = Inventory::orderBy('name')->get();
        return view('inventories.index', compact('inventories'));
    }

    public function create()
    {
        return view('inventories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:inventories,name',
            'stock' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'min_stock' => 'required|numeric|min:0',
        ]);

        Inventory::create($request->only('name', 'stock', 'unit', 'min_stock'));

        return redirect()->route('inventories.index')->with('success', 'Bahan baku berhasil ditambahkan!');
    }

    public function edit(Inventory $inventory)
    {
        return view('inventories.edit', compact('inventory'));
    }

    public function update(Request $request, Inventory $inventory)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:inventories,name,' . $inventory->id,
            'stock' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'min_stock' => 'required|numeric|min:0',
        ]);

        $inventory->update($request->only('name', 'stock', 'unit', 'min_stock'));

        return redirect()->route('inventories.index')->with('success', 'Bahan baku berhasil diperbarui!');
    }

    public function destroy(Inventory $inventory)
    {
        $inventory->delete();
        return redirect()->route('inventories.index')->with('success', 'Bahan baku berhasil dihapus!');
    }
}
