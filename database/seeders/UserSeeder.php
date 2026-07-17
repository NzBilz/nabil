<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'owner@tehpoci.com'],
            [
                'name' => 'Owner Teh Poci',
                'password' => Hash::make('password'),
                'role' => 'owner',
            ]
        );

        User::updateOrCreate(
            ['email' => 'kasir@tehpoci.com'],
            [
                'name' => 'Kasir Teh Poci',
                'password' => Hash::make('password'),
                'role' => 'kasir',
            ]
        );
    }
}
