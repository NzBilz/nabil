<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            ['name' => 'Original', 'size' => 'Medium', 'price' => 5000.00],
            ['name' => 'Original', 'size' => 'Large', 'price' => 7000.00],
            ['name' => 'Jasmine', 'size' => 'Medium', 'price' => 6000.00],
            ['name' => 'Jasmine', 'size' => 'Large', 'price' => 8000.00],
            ['name' => 'Milk Tea', 'size' => 'Medium', 'price' => 8000.00],
            ['name' => 'Milk Tea', 'size' => 'Large', 'price' => 10000.00],
        ];

        foreach ($menus as $menu) {
            Menu::updateOrCreate(
                ['name' => $menu['name'], 'size' => $menu['size']],
                ['price' => $menu['price']]
            );
        }
    }
}
