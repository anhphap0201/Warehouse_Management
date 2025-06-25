<x-app-layout>
    <x-slot name="header">
        <div class="page-header-standard">
            <div class="page-header-content">
                <div class="page-title-section">
                    <h1 class="page-title-main text-gray-900">
                        <div class="page-title-icon simple-bg">
                            <i class="fas fa-tachometer-alt text-blue-600 text-lg"></i>
                        </div>
                        {{ __('app.dashboard') }}
                    </h1>
                    <p class="page-subtitle">Tổng quan hệ thống quản lý kho hàng</p>
                </div>
                <div class="text-sm text-gray-500">
                    Cập nhật: {{ now()->format('d/m/Y H:i') }}
                </div>
            </div>
        </div>
    </x-slot>

    <div class="page-container">
        <div class="page-content">
            <!-- Modern Stats Grid -->
            <div class="stats-grid">
                <!-- Products Stats Card -->
                <div class="stats-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="stats-card-icon bg-blue-600">
                                <i class="fas fa-box"></i>
                            </div>
                            <div class="stats-card-value">{{ App\Models\Product::count() }}</div>
                            <div class="stats-card-label">Tổng số sản phẩm</div>
                        </div>
                        <div class="text-right">
                            <div class="text-xs text-green-600 font-medium">
                                <i class="fas fa-arrow-up mr-1"></i>+12%
                            </div>
                            <div class="text-xs text-gray-500">vs tháng trước</div>
                        </div>
                    </div>
                </div>

                <!-- Categories Stats Card -->
                <div class="stats-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="stats-card-icon bg-green-600">
                                <i class="fas fa-tags"></i>
                            </div>
                            <div class="stats-card-value">{{ App\Models\Category::count() }}</div>
                            <div class="stats-card-label">Danh mục sản phẩm</div>
                        </div>
                        <div class="text-right">
                            <div class="text-xs text-green-600 font-medium">
                                <i class="fas fa-arrow-up mr-1"></i>+5%
                            </div>
                            <div class="text-xs text-gray-500">vs tháng trước</div>
                        </div>
                    </div>
                </div>

                <!-- Warehouses Stats Card -->
                <div class="stats-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="stats-card-icon bg-purple-600">
                                <i class="fas fa-warehouse"></i>
                            </div>
                            <div class="stats-card-value">{{ App\Models\Warehouse::count() }}</div>
                            <div class="stats-card-label">Kho hàng</div>
                        </div>
                        <div class="text-right">
                            <div class="text-xs text-blue-600 font-medium">
                                <i class="fas fa-minus mr-1"></i>Ổn định
                            </div>
                            <div class="text-xs text-gray-500">không đổi</div>
                        </div>
                    </div>
                </div>

                <!-- Stores Stats Card -->
                <div class="stats-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="stats-card-icon bg-orange-600">
                                <i class="fas fa-store"></i>
                            </div>
                            <div class="stats-card-value">{{ App\Models\Store::count() }}</div>
                            <div class="stats-card-label">Cửa hàng</div>
                        </div>
                        <div class="text-right">
                            <div class="text-xs text-green-600 font-medium">
                                <i class="fas fa-arrow-up mr-1"></i>+8%
                            </div>
                            <div class="text-xs text-gray-500">vs tháng trước</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Section -->
            <div class="actions-grid">
                <!-- Product Management Card -->
                <div class="content-card">
                    <div class="content-card-header">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-box mr-2 text-blue-600"></i>
                            Quản lý sản phẩm
                        </h3>
                    </div>
                    <div class="card-body">
                        <p class="text-gray-600 text-sm mb-4">Quản lý danh sách sản phẩm và danh mục</p>
                        <div class="space-y-2">
                            <a href="{{ route('products.index') }}" class="btn btn-primary w-full">
                                <i class="fas fa-list mr-2"></i>
                                Xem sản phẩm
                            </a>
                            <a href="{{ route('products.create') }}" class="btn btn-outline w-full">
                                <i class="fas fa-plus mr-2"></i>
                                Thêm sản phẩm
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Warehouse Management Card -->
                <div class="content-card">
                    <div class="content-card-header">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-warehouse mr-2 text-purple-600"></i>
                            Quản lý kho
                        </h3>
                    </div>
                    <div class="card-body">
                        <p class="text-gray-600 text-sm mb-4">Quản lý kho hàng và tồn kho</p>
                        <div class="space-y-2">
                            <a href="{{ route('warehouses.index') }}" class="btn btn-primary w-full">
                                <i class="fas fa-list mr-2"></i>
                                Xem kho hàng
                            </a>
                            <a href="{{ route('warehouses.create') }}" class="btn btn-outline w-full">
                                <i class="fas fa-plus mr-2"></i>
                                Thêm kho
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Purchase Orders Card -->
                <div class="content-card">
                    <div class="content-card-header">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-shopping-cart mr-2 text-green-600"></i>
                            Nhập hàng
                        </h3>
                    </div>
                    <div class="card-body">
                        <p class="text-gray-600 text-sm mb-4">Quản lý đơn hàng nhập kho</p>
                        <div class="space-y-2">
                            <a href="{{ route('purchase-orders.index') }}" class="btn btn-primary w-full">
                                <i class="fas fa-list mr-2"></i>
                                Xem đơn nhập
                            </a>
                            <a href="{{ route('purchase-orders.create') }}" class="btn btn-outline w-full">
                                <i class="fas fa-plus mr-2"></i>
                                Tạo đơn nhập
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity & Analytics -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Products -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-clock mr-2 text-blue-600"></i>
                            Sản phẩm mới nhất
                        </h3>
                    </div>
                    <div class="card-body">
                        @if(App\Models\Product::count() > 0)
                            <div class="space-y-3">
                                @foreach(App\Models\Product::latest()->take(5)->get() as $product)
                                    <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-gray-200 rounded-lg flex items-center justify-center mr-3">
                                                <i class="fas fa-box text-gray-600 text-xs"></i>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-900 text-sm">{{ $product->name }}</p>
                                                <p class="text-gray-500 text-xs">SKU: {{ $product->sku }}</p>
                                            </div>
                                        </div>
                                        <span class="text-xs text-gray-500">{{ $product->created_at->diffForHumans() }}</span>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('products.index') }}" class="text-blue-600 text-sm font-medium hover:text-blue-700">
                                    Xem tất cả →
                                </a>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="fas fa-box text-gray-300 text-3xl mb-3"></i>
                                <p class="text-gray-500">Chưa có sản phẩm nào</p>
                                <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm mt-3">
                                    Thêm sản phẩm đầu tiên
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- System Overview -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-chart-bar mr-2 text-green-600"></i>
                            Tổng quan hệ thống
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="space-y-4">
                            <!-- Storage Utilization -->
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm font-medium text-gray-700">Sử dụng kho</span>
                                    <span class="text-sm text-gray-500">75%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: 75%"></div>
                                </div>
                            </div>

                            <!-- Active Warehouses -->
                            <div class="flex items-center justify-between py-2">
                                <span class="text-sm text-gray-600">Kho hoạt động</span>
                                <span class="text-sm font-semibold text-gray-900">{{ App\Models\Warehouse::count() }}/{{ App\Models\Warehouse::count() }}</span>
                            </div>

                            <!-- Total Inventory Items -->
                            <div class="flex items-center justify-between py-2">
                                <span class="text-sm text-gray-600">Mặt hàng tồn kho</span>
                                <span class="text-sm font-semibold text-gray-900">{{ App\Models\Inventory::sum('quantity') }}</span>
                            </div>

                            <!-- Low Stock Alert -->
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                                <div class="flex items-center">
                                    <i class="fas fa-exclamation-triangle text-yellow-600 mr-2"></i>
                                    <span class="text-sm text-yellow-800 font-medium">
                                        {{ App\Models\Inventory::where('quantity', '<', 10)->count() }} sản phẩm sắp hết hàng
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
