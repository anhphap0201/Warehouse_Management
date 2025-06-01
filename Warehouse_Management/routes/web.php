<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
// Existing controllers only
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Product\CategoryController;
use App\Http\Controllers\Inventory\WarehouseController;
use App\Http\Controllers\Store\StoreController;
// Auth controllers
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;

// Landing Page
Route::get('/', function () {
    return view('welcome');
});
// Trang chủ
Route::get('/dashboard', function () {
    $warehouses = \App\Models\Warehouse::latest()->take(5)->get();
    $stores = \App\Models\Store::latest()->take(5)->get();
    return view('dashboard', compact('warehouses', 'stores'));
})->middleware(['auth', 'verified'])->name('dashboard');

// Xác thực - Simplified
Route::middleware('guest')->group(function () {
    // Basic auth will be handled by auth.php
});

// Yêu cầu đăng nhập
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Sản phẩm - Chỉ xem
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
    
    // Danh mục - Chỉ xem
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
    
    // Kho hàng - CRUD đầy đủ
    Route::get('/warehouses', [WarehouseController::class, 'index'])->name('warehouses.index');
    Route::get('/warehouses/create', [WarehouseController::class, 'create'])->name('warehouses.create');
    Route::post('/warehouses', [WarehouseController::class, 'store'])->name('warehouses.store');
    Route::get('/warehouses/{warehouse}', [WarehouseController::class, 'show'])->name('warehouses.show');
    Route::get('/warehouses/{warehouse}/edit', [WarehouseController::class, 'edit'])->name('warehouses.edit');
    Route::put('/warehouses/{warehouse}', [WarehouseController::class, 'update'])->name('warehouses.update');
    Route::delete('/warehouses/{warehouse}', [WarehouseController::class, 'destroy'])->name('warehouses.destroy');
    
    // Cửa hàng - Chỉ xem
    Route::get('/stores', [StoreController::class, 'index'])->name('stores.index');
    Route::get('/stores/{store}', [StoreController::class, 'show'])->name('stores.show');
});
require __DIR__.'/auth.php';
