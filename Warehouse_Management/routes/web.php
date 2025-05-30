<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Product\CategoryController;
use App\Http\Controllers\Inventory\InventoryController;
use App\Http\Controllers\Inventory\WarehouseController;
use App\Http\Controllers\Inventory\TransferController;
use App\Http\Controllers\Store\StoreController;
use App\Http\Controllers\Movement\StockMovementController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SessionController;
use App\Http\Controllers\Admin\CacheController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Report\InventoryReportController;
use App\Http\Controllers\Report\MovementReportController;

// Landing Page
Route::get('/', function () {
    return view('welcome');
});
// Trang chủ
Route::get('/dashboard', function () {
    $warehouses = \App\Models\Warehouse::latest()->take(5)->get();
    $stores = \App\Models\Store::latest()->take(5)->get();
    $products = \App\Models\Product::with('category')->latest()->take(5)->get();
    return view('dashboard', compact('warehouses', 'stores', 'products'));
})->middleware(['auth', 'verified'])->name('dashboard');

// Xác thực
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// Yêu cầu đăng nhập
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Sản phẩm - Chỉ xem
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
    
    // Danh mục - Chỉ xem
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
    
    // Kho - Chỉ xem
    Route::get('/warehouses', [WarehouseController::class, 'index'])->name('warehouses.index');
    Route::get('/warehouses/{warehouse}', [WarehouseController::class, 'show'])->name('warehouses.show');
    
    // Tồn kho - Chỉ xem
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    
    // Cửa hàng - Chỉ xem
    Route::get('/stores', [StoreController::class, 'index'])->name('stores.index');
    Route::get('/stores/{store}', [StoreController::class, 'show'])->name('stores.show');
    
    // Chuyển động kho - Chỉ xem
    Route::get('/movements', [StockMovementController::class, 'index'])->name('movements.index');
    Route::get('/movements/{movement}', [StockMovementController::class, 'show'])->name('movements.show');
    
    // Nhân viên (staff) và admin
    Route::middleware('staff')->group(function () {
        // Nhận hàng tại cửa hàng
        Route::get('/stores/{store}/receive', [StoreController::class, 'showReceiveForm'])->name('stores.receive.form');
        Route::post('/stores/{store}/receive', [StoreController::class, 'receiveStock'])->name('stores.receive');
        
        // Trả hàng về kho
        Route::get('/stores/{store}/return', [StoreController::class, 'showReturnForm'])->name('stores.return.form');
        Route::post('/stores/{store}/return', [StoreController::class, 'returnStock'])->name('stores.return');
        
        // Chuyển hàng từ kho đến cửa hàng
        Route::get('/transfer', [TransferController::class, 'showTransferForm'])->name('transfer.form');
        Route::post('/transfer', [TransferController::class, 'transferToStore'])->name('transfer.store');
        
        // Ghi nhận chuyển động kho
        Route::get('/movements/create', [StockMovementController::class, 'create'])->name('movements.create');
        Route::post('/movements', [StockMovementController::class, 'store'])->name('movements.store');
        
        // Hoàn tác chuyển động kho
        Route::post('/movements/{movement}/reverse', [StockMovementController::class, 'reverse'])->name('movements.reverse');
    });
    
    // Chỉ Admin
    Route::middleware('admin')->group(function () {
        // Dashboard
        Route::get('/admin', [DashboardController::class, 'index'])->name('admin.dashboard');
        
        // Quản lý sản phẩm
        Route::resource('admin/products', ProductController::class, ['as' => 'admin'])->except(['index', 'show']);
        
        // Quản lý danh mục
        Route::resource('admin/categories', CategoryController::class, ['as' => 'admin'])->except(['index', 'show']);
        
        // Quản lý kho
        Route::resource('admin/warehouses', WarehouseController::class, ['as' => 'admin'])->except(['index', 'show']);
        
        // Quản lý cửa hàng
        Route::resource('admin/stores', StoreController::class, ['as' => 'admin'])->except(['index', 'show']);
        
        // Điều chỉnh tồn kho
        Route::get('/admin/inventory/adjust', [InventoryController::class, 'showAdjustForm'])->name('admin.inventory.adjust.form');
        Route::post('/admin/inventory/adjust', [InventoryController::class, 'adjust'])->name('admin.inventory.adjust');
        
        // Quản lý người dùng
        Route::resource('admin/users', UserController::class, ['as' => 'admin']);
        
        // Quản lý phiên làm việc
        Route::get('/admin/sessions', [SessionController::class, 'index'])->name('admin.sessions.index');
        Route::delete('/admin/sessions/{session}', [SessionController::class, 'destroy'])->name('admin.sessions.destroy');
        
        // Quản lý cache
        Route::get('/admin/cache', [CacheController::class, 'index'])->name('admin.cache.index');
        Route::post('/admin/cache/clear', [CacheController::class, 'clear'])->name('admin.cache.clear');
        
        // Báo cáo
        Route::get('/admin/reports/inventory', [InventoryReportController::class, 'index'])->name('admin.reports.inventory');
        Route::get('/admin/reports/movements', [MovementReportController::class, 'index'])->name('admin.reports.movements');
    });
});
require __DIR__.'/auth.php';
