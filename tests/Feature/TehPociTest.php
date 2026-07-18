<?php

use App\Models\User;
use App\Models\Menu;
use App\Models\Inventory;
use App\Models\Recipe;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows owner to access owner pages and denies cashier', function () {
    $owner = User::factory()->create(['role' => 'owner']);
    $kasir = User::factory()->create(['role' => 'kasir']);

    // Kasir cannot access owner dashboard and management
    $this->actingAs($kasir)->get(route('dashboard'))->assertRedirect(route('checkout.index'));
    $this->actingAs($kasir)->get(route('menus.index'))->assertStatus(403);
    $this->actingAs($kasir)->get(route('inventories.index'))->assertStatus(200);
    $this->actingAs($kasir)->get(route('checkout.history'))->assertStatus(403);

    // Owner can access owner dashboard and management
    $this->actingAs($owner)->get(route('dashboard'))->assertStatus(200);
    $this->actingAs($owner)->get(route('menus.index'))->assertStatus(200);
    $this->actingAs($owner)->get(route('inventories.index'))->assertStatus(200);
    $this->actingAs($owner)->get(route('checkout.history'))->assertStatus(200);
});

it('allows cashier to access checkout page and denies owner', function () {
    $owner = User::factory()->create(['role' => 'owner']);
    $kasir = User::factory()->create(['role' => 'kasir']);

    // Kasir can access checkout
    $this->actingAs($kasir)->get(route('checkout.index'))->assertStatus(200);

    // Owner cannot access checkout
    $this->actingAs($owner)->get(route('checkout.index'))->assertStatus(403);
});

it('deducts inventory stock automatically on checkout', function () {
    $kasir = User::factory()->create(['role' => 'kasir']);

    $menu = Menu::create(['name' => 'Original Poci', 'size' => 'Medium', 'price' => 5000]);
    $cup = Inventory::create(['name' => 'Cup Medium', 'stock' => 10, 'unit' => 'pcs', 'min_stock' => 2]);
    $tea = Inventory::create(['name' => 'Tea Powder', 'stock' => 100, 'unit' => 'gram', 'min_stock' => 20]);

    Recipe::create(['menu_id' => $menu->id, 'inventory_id' => $cup->id, 'quantity' => 1]);
    Recipe::create(['menu_id' => $menu->id, 'inventory_id' => $tea->id, 'quantity' => 15]);

    $payload = [
        'items' => [
            [
                'menu_id' => $menu->id,
                'quantity' => 2,
            ]
        ],
        'payment_amount' => 15000,
    ];

    $response = $this->actingAs($kasir)->post(route('checkout.store'), $payload);

    $response->assertRedirect(route('checkout.index'));
    $response->assertSessionHasNoErrors();
    $response->assertSessionHas('success');

    // Assert databases
    $this->assertDatabaseHas('transactions', [
        'total_amount' => 10000,
        'payment_amount' => 15000,
        'change_amount' => 5000,
    ]);

    // Check inventory stock decrements
    $this->assertEquals(8, $cup->fresh()->stock);       // 10 - (2 * 1)
    $this->assertEquals(70, $tea->fresh()->stock);     // 100 - (2 * 15)
});

it('rolls back transaction and denies checkout when stock is insufficient', function () {
    $kasir = User::factory()->create(['role' => 'kasir']);

    $menu = Menu::create(['name' => 'Original Poci', 'size' => 'Medium', 'price' => 5000]);
    $cup = Inventory::create(['name' => 'Cup Medium', 'stock' => 1, 'unit' => 'pcs', 'min_stock' => 0]);

    Recipe::create(['menu_id' => $menu->id, 'inventory_id' => $cup->id, 'quantity' => 1]);

    $payload = [
        'items' => [
            [
                'menu_id' => $menu->id,
                'quantity' => 2, // Requires 2 cups, only 1 in stock!
            ]
        ],
        'payment_amount' => 10000,
    ];

    $response = $this->actingAs($kasir)->from(route('checkout.index'))->post(route('checkout.store'), $payload);

    $response->assertRedirect(route('checkout.index'));
    $response->assertSessionHas('error');
    
    // Ensure no transactions were recorded
    $this->assertDatabaseCount('transactions', 0);
    $this->assertDatabaseCount('transaction_details', 0);

    // Stock should not change
    $this->assertEquals(1, $cup->fresh()->stock);
});

it('allows both owner and cashier to access and manage recipes', function () {
    $owner = User::factory()->create(['role' => 'owner']);
    $kasir = User::factory()->create(['role' => 'kasir']);

    $menu = Menu::create(['name' => 'Original Poci', 'size' => 'Medium', 'price' => 5000]);
    $cup = Inventory::create(['name' => 'Cup Medium', 'stock' => 10, 'unit' => 'pcs', 'min_stock' => 2]);

    // Check index access
    $this->actingAs($owner)->get(route('recipes.index'))->assertStatus(200);
    $this->actingAs($kasir)->get(route('recipes.index'))->assertStatus(200);

    // Check edit access
    $this->actingAs($owner)->get(route('recipes.edit', $menu->id))->assertStatus(200);
    $this->actingAs($kasir)->get(route('recipes.edit', $menu->id))->assertStatus(200);

    // Update recipe via controller
    $payload = [
        'ingredients' => [
            [
                'inventory_id' => $cup->id,
                'quantity' => 1.5,
            ]
        ]
    ];

    $response = $this->actingAs($kasir)->put(route('recipes.update', $menu->id), $payload);
    $response->assertRedirect(route('recipes.index'));
    $response->assertSessionHas('success');

    // Assert pivot table was updated
    $this->assertDatabaseHas('recipes', [
        'menu_id' => $menu->id,
        'inventory_id' => $cup->id,
        'quantity' => 1.5,
    ]);

    // Verify checkout uses the new recipe quantity (1.5 cups per menu, order 2 cups = 3 cups total deduction)
    $checkoutPayload = [
        'items' => [
            [
                'menu_id' => $menu->id,
                'quantity' => 2,
            ]
        ],
        'payment_amount' => 10000,
    ];

    $checkoutResponse = $this->actingAs($kasir)->post(route('checkout.store'), $checkoutPayload);
    $checkoutResponse->assertRedirect(route('checkout.index'));
    $this->assertEquals(7, $cup->fresh()->stock); // 10 - (2 * 1.5) = 7
});

it('denies checkout when payment amount is less than total price', function () {
    $kasir = User::factory()->create(['role' => 'kasir']);

    $menu = Menu::create(['name' => 'Original Poci', 'size' => 'Medium', 'price' => 5000]);
    $cup = Inventory::create(['name' => 'Cup Medium', 'stock' => 10, 'unit' => 'pcs', 'min_stock' => 0]);
    Recipe::create(['menu_id' => $menu->id, 'inventory_id' => $cup->id, 'quantity' => 1]);

    $payload = [
        'items' => [
            [
                'menu_id' => $menu->id,
                'quantity' => 2, // Total price = 10000
            ]
        ],
        'payment_amount' => 8000, // Insufficient payment
    ];

    $response = $this->actingAs($kasir)->from(route('checkout.index'))->post(route('checkout.store'), $payload);

    $response->assertRedirect(route('checkout.index'));
    $response->assertSessionHas('error', 'Uang anda kurang!');

    // Ensure no transactions were recorded
    $this->assertDatabaseCount('transactions', 0);
    $this->assertDatabaseCount('transaction_details', 0);

    // Stock should not change
    $this->assertEquals(10, $cup->fresh()->stock);
});
