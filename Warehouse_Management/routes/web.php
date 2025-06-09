<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
// Chỉ sử dụng các controller hiện có
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Product\CategoryController;
use App\Http\Controllers\Inventory\WarehouseController;
use App\Http\Controllers\Store\StoreController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\NotificationController;
// Controllers xác thực
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;

// Trang chủ
Route::get('/', function () {
    return view('welcome');
});
// Trang chủ
Route::get('/dashboard', function () {
    $warehouses = \App\Models\Warehouse::latest()->take(5)->get();
    $stores = \App\Models\Store::latest()->take(5)->get();
    return view('dashboard', compact('warehouses', 'stores'));
})->middleware(['auth', 'verified'])->name('dashboard');

// Xác thực - Đơn giản hóa
Route::middleware('guest')->group(function () {
    // Xác thực cơ bản sẽ được xử lý bởi auth.php
});

// Yêu cầu đăng nhập
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Sản phẩm - CRUD đầy đủ
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    
    // Danh mục - CRUD đầy đủ
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    
    // Kho hàng - CRUD đầy đủ
    Route::get('/warehouses', [WarehouseController::class, 'index'])->name('warehouses.index');
    Route::get('/warehouses/create', [WarehouseController::class, 'create'])->name('warehouses.create');
    Route::post('/warehouses', [WarehouseController::class, 'store'])->name('warehouses.store');
    Route::get('/warehouses/{warehouse}', [WarehouseController::class, 'show'])->name('warehouses.show');
    Route::get('/warehouses/{warehouse}/edit', [WarehouseController::class, 'edit'])->name('warehouses.edit');
    Route::put('/warehouses/{warehouse}', [WarehouseController::class, 'update'])->name('warehouses.update');
    Route::delete('/warehouses/{warehouse}', [WarehouseController::class, 'destroy'])->name('warehouses.destroy');
    
    // Cửa hàng - CRUD đầy đủ
    Route::get('/stores', [StoreController::class, 'index'])->name('stores.index');
    Route::get('/stores/create', [StoreController::class, 'create'])->name('stores.create');
    Route::post('/stores', [StoreController::class, 'store'])->name('stores.store');
    Route::get('/stores/{store}', [StoreController::class, 'show'])->name('stores.show');
    Route::get('/stores/{store}/edit', [StoreController::class, 'edit'])->name('stores.edit');
    Route::put('/stores/{store}', [StoreController::class, 'update'])->name('stores.update');
    Route::delete('/stores/{store}', [StoreController::class, 'destroy'])->name('stores.destroy');
    
    // Nhà cung cấp - CRUD đầy đủ
    Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
    Route::get('/suppliers/create', [SupplierController::class, 'create'])->name('suppliers.create');
    Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
    Route::get('/suppliers/{supplier}', [SupplierController::class, 'show'])->name('suppliers.show');
    Route::get('/suppliers/{supplier}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');
    Route::put('/suppliers/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');
    Route::delete('/suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');
    
    // Hóa đơn nhập kho - CRUD đầy đủ
    Route::get('/purchase-orders', [PurchaseOrderController::class, 'index'])->name('purchase-orders.index');
    Route::get('/purchase-orders/create', [PurchaseOrderController::class, 'create'])->name('purchase-orders.create');
    Route::post('/purchase-orders', [PurchaseOrderController::class, 'store'])->name('purchase-orders.store');
    Route::get('/purchase-orders/{purchaseOrder}', [PurchaseOrderController::class, 'show'])->name('purchase-orders.show');
    Route::delete('/purchase-orders/{purchaseOrder}', [PurchaseOrderController::class, 'destroy'])->name('purchase-orders.destroy');
    
    // Hoạt động tồn kho và vận hành kho của cửa hàng
    Route::get('/stores/{store}/receive', [StoreController::class, 'showReceiveForm'])->name('stores.receive.form');
    Route::post('/stores/{store}/receive', [StoreController::class, 'receiveStock'])->name('stores.receive');
    Route::get('/stores/{store}/return', [StoreController::class, 'showReturnForm'])->name('stores.return.form');
    Route::post('/stores/{store}/return', [StoreController::class, 'returnStock'])->name('stores.return');
    
    // Các route của hệ thống thông báo
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/create', [NotificationController::class, 'create'])->name('notifications.create');
    Route::post('/notifications', [NotificationController::class, 'store'])->name('notifications.store');
    Route::get('/notifications/{notification}', [NotificationController::class, 'show'])->name('notifications.show');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::post('/notifications/{notification}/approve', [NotificationController::class, 'approve'])->name('notifications.approve');
    Route::post('/notifications/{notification}/reject', [NotificationController::class, 'reject'])->name('notifications.reject');
    
    // Các route API cho thông báo
    Route::get('/api/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('api.notifications.unread-count');
    Route::post('/api/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('api.notifications.mark-all-read');
    Route::get('/api/warehouses', [NotificationController::class, 'getWarehouses'])->name('api.warehouses.list');
    
    // Các route API cho tìm kiếm thời gian thực
    Route::get('/api/products/search', [PurchaseOrderController::class, 'searchProducts'])->name('api.products.search');
    Route::get('/api/products/{id}', [ProductController::class, 'getProduct'])->name('api.products.get');
    Route::get('/api/warehouses/search', [PurchaseOrderController::class, 'searchWarehouses'])->name('api.warehouses.search'); 
    Route::get('/api/warehouses/{warehouse}', [WarehouseController::class, 'getWarehouse'])->name('api.warehouses.get');
    Route::get('/api/suppliers/search', [SupplierController::class, 'search'])->name('api.suppliers.search');
    
    // Các route tự động tạo
    Route::prefix('admin/auto-generation')->name('admin.auto-generation.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\AutoGenerationController::class, 'index'])->name('index');
        
        // Tạo đơn hàng thử nghiệm (đơn giản hóa)
        Route::post('/test-return', [App\Http\Controllers\Admin\AutoGenerationController::class, 'createTestReturnOrders'])->name('test-return');
        Route::post('/test-shipment', [App\Http\Controllers\Admin\AutoGenerationController::class, 'createTestShipmentOrders'])->name('test-shipment');
    });
});
require __DIR__.'/auth.php';
