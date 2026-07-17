<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ProfileController;
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
        Route::resource('inventories', InventoryController::class);
    });

    // KASIR Routes (Checkout Screen & Transaction Execution)
    Route::middleware('role.kasir')->group(function () {
        Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
        Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    });
});

require __DIR__.'/auth.php';
