<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecipeController;
use Illuminate\Support\Facades\Route;

// Redirect logic on root URL
Route::get('/', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }
    if (auth()->user()->isOwner()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('checkout.index');
})->name('home');

// Auth routes scaffolded by Laravel Breeze
Route::middleware('auth')->group(function () {
    // Profile management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dynamic Dashboard Route based on User Role
    Route::get('/dashboard', function () {
        if (auth()->user()->isOwner()) {
            return app(DashboardController::class)->index();
        }
        return redirect()->route('checkout.index');
    })->name('dashboard');

    // OWNER Routes (Reports, Stock/Menu CRUD)
    Route::middleware('role.owner')->group(function () {
        Route::get('/history', [CheckoutController::class, 'history'])->name('checkout.history');
        Route::resource('menus', MenuController::class);
    });

    // SHARED / DEMO Routes (Accessible by Owner & Kasir for assignment requirements)
    Route::resource('inventories', InventoryController::class);
    Route::get('/recipes', [RecipeController::class, 'index'])->name('recipes.index');
    Route::get('/recipes/{menu}/edit', [RecipeController::class, 'edit'])->name('recipes.edit');
    Route::put('/recipes/{menu}', [RecipeController::class, 'update'])->name('recipes.update');
    Route::get('/transaksi', [\App\Http\Controllers\TransactionController::class, 'index'])->name('transactions.index');

    // KASIR Routes (Checkout Screen & Transaction Execution)
    Route::middleware('role.kasir')->group(function () {
        Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
        Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
        Route::post('/checkout/menus', [CheckoutController::class, 'storeMenu'])->name('checkout.menus.store');
        Route::delete('/checkout/menus/{menu}', [CheckoutController::class, 'destroyMenu'])->name('checkout.menus.destroy');
    });
});

require __DIR__.'/auth.php';
