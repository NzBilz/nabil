<?php

namespace Database\Seeders;

use App\Models\Inventory;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['name' => 'Cup Medium', 'stock' => 100, 'unit' => 'pcs', 'min_stock' => 20],
            ['name' => 'Cup Large', 'stock' => 100, 'unit' => 'pcs', 'min_stock' => 20],
            ['name' => 'Sedotan', 'stock' => 200, 'unit' => 'pcs', 'min_stock' => 30],
            ['name' => 'Bubuk Teh Original', 'stock' => 1000, 'unit' => 'gram', 'min_stock' => 200],
            ['name' => 'Bubuk Teh Jasmine', 'stock' => 1000, 'unit' => 'gram', 'min_stock' => 200],
            ['name' => 'Bubuk Teh Milk Tea', 'stock' => 1000, 'unit' => 'gram', 'min_stock' => 200],
            ['name' => 'Gula Cair', 'stock' => 5000, 'unit' => 'ml', 'min_stock' => 1000],
            ['name' => 'Es Batu', 'stock' => 500, 'unit' => 'pcs', 'min_stock' => 100],
        ];

        foreach ($items as $item) {
            Inventory::updateOrCreate(['name' => $item['name']], $item);
        }
    }
}
