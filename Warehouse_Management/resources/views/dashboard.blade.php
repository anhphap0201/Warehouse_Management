<x-app-layout>
    <x-slot name="header">        <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-100 leading-tight flex items-center">
            <i class="fas fa-tachometer-alt mr-2 text-blue-600 dark:text-blue-400"></i>
            {{ __('app.dashboard') }}
        </h2>
    </x-slot>    <div class="py-4 sm:py-6">
        <div class="w-full max-w-7xl mx-auto px-2 sm:px-4 lg:px-8">
            <!-- Summary Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-4 sm:mb-6">
                <!-- Total Products Card -->
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg overflow-hidden text-white">
                    <div class="px-3 py-4 sm:px-4 sm:py-5 lg:p-6 flex justify-between items-center">                        <div>
                            <dt class="text-xs sm:text-sm font-medium truncate">
                                Tổng số sản phẩm
                            </dt>
                            <dd class="mt-1 text-xl sm:text-2xl lg:text-3xl font-semibold">
                                {{ App\Models\Product::count() }}
                            </dd>
                        </div>
                        <div class="rounded-full bg-white bg-opacity-20 p-2 sm:p-3">
                            <i class="fas fa-box text-lg sm:text-xl lg:text-2xl"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Total Categories Card -->
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg overflow-hidden text-white">
                    <div class="px-3 py-4 sm:px-4 sm:py-5 lg:p-6 flex justify-between items-center">                        <div>
                            <dt class="text-xs sm:text-sm font-medium truncate">
                                Danh mục
                            </dt>
                            <dd class="mt-1 text-xl sm:text-2xl lg:text-3xl font-semibold">
                                {{ App\Models\Category::count() }}
                            </dd>
                        </div>
                        <div class="rounded-full bg-white bg-opacity-20 p-2 sm:p-3">
                            <i class="fas fa-list-ul text-lg sm:text-xl lg:text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Total Warehouses Card -->
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg overflow-hidden text-white">
                    <div class="px-3 py-4 sm:px-4 sm:py-5 lg:p-6 flex justify-between items-center">                        <div>
                            <dt class="text-xs sm:text-sm font-medium truncate">
                                Kho hàng
                            </dt>
                            <dd class="mt-1 text-xl sm:text-2xl lg:text-3xl font-semibold">
                                {{ App\Models\Warehouse::count() }}
                            </dd>
                        </div>
                        <div class="rounded-full bg-white bg-opacity-20 p-2 sm:p-3">
                            <i class="fas fa-warehouse text-lg sm:text-xl lg:text-2xl"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Total Stores Card -->
                <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow-lg overflow-hidden text-white">
                    <div class="px-3 py-4 sm:px-4 sm:py-5 lg:p-6 flex justify-between items-center">                        <div>
                            <dt class="text-xs sm:text-sm font-medium truncate">
                                Cửa hàng
                            </dt>
                            <dd class="mt-1 text-xl sm:text-2xl lg:text-3xl font-semibold">
                                {{ App\Models\Store::count() }}
                            </dd>
                        </div>
                        <div class="rounded-full bg-white bg-opacity-20 p-2 sm:p-3">
                            <i class="fas fa-store text-lg sm:text-xl lg:text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Grid Layout 2 cột -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <!-- Cột 1: Quản lý Kho Hàng -->
                <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-lg rounded-lg border border-gray-100 dark:border-slate-700">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="mb-6">
                            <h3 class="text-xl font-semibold mb-4 flex items-center text-blue-600 dark:text-blue-400 border-b border-gray-200 dark:border-slate-700 pb-2">
                                <i class="fas fa-warehouse mr-2"></i>
                                {{ __('Quản lý Kho Hàng') }}
                            </h3>
                            
                            @if($warehouses->count() > 0)
                                <div class="space-y-3 mb-4">
                                    @foreach($warehouses as $warehouse)
                                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h4 class="font-semibold text-gray-900 dark:text-gray-100">
                                                    {{ $warehouse->name }}
                                                </h4>
                                                <p class="text-sm text-gray-500 dark:text-gray-300 mt-1">
                                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    </svg>
                                                    {{ $warehouse->location ?? 'Chưa cập nhật' }}
                                                </p>
                                                <p class="text-xs text-gray-400 mt-1">
                                                    Tạo: {{ $warehouse->created_at->format('d/m/Y') }}
                                                </p>
                                            </div>
                                            <div class="flex space-x-2">
                                                <a href="{{ route('warehouses.show', $warehouse) }}" 
                                                   class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm">
                                                    Chi tiết
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                
                                <div class="flex justify-center">
                                    <a href="{{ route('warehouses.index') }}" 
                                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                                        </svg>
                                        Xem tất cả kho hàng
                                    </a>
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <div class="inline-flex items-center justify-center w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-full mb-3">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                    </div>
                                    <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-2">
                                        Chưa có kho hàng nào
                                    </h4>
                                    <a href="{{ route('warehouses.index') }}" 
                                       class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md">
                                        Xem trang kho hàng
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Cột 2: Quản lý Cửa Hàng -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-4 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                {{ __('Quản lý Cửa Hàng') }}
                            </h3>
                            
                            @if($stores->count() > 0)
                                <div class="space-y-3 mb-4">
                                    @foreach($stores as $store)
                                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <div class="flex items-center">
                                                    <h4 class="font-semibold text-gray-900 dark:text-gray-100">
                                                        {{ $store->name }}
                                                    </h4>
                                                    <span class="ml-2 px-2 py-1 text-xs rounded-full {{ $store->status ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                                        {{ $store->status ? 'Hoạt động' : 'Tạm đóng' }}
                                                    </span>
                                                </div>
                                                <p class="text-sm text-gray-500 dark:text-gray-300 mt-1">
                                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    </svg>
                                                    {{ $store->location ?? 'Chưa cập nhật' }}
                                                </p>
                                                @if($store->manager)
                                                <p class="text-sm text-gray-500 dark:text-gray-300 mt-1">
                                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                    </svg>
                                                    QL: {{ $store->manager }}
                                                </p>
                                                @endif
                                                <p class="text-xs text-gray-400 mt-1">
                                                    Tạo: {{ $store->created_at->format('d/m/Y') }}
                                                </p>
                                            </div>
                                            <div class="flex space-x-2">
                                                <a href="{{ route('stores.show', $store) }}" 
                                                   class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 text-sm">
                                                    Chi tiết
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                
                                <div class="flex justify-center">
                                    <a href="{{ route('stores.index') }}" 
                                       class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                                        </svg>
                                        Xem tất cả cửa hàng
                                    </a>
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <div class="inline-flex items-center justify-center w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-full mb-3">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                    </div>
                                    <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-2">
                                        Chưa có cửa hàng nào
                                    </h4>
                                    <a href="{{ route('stores.index') }}" 
                                       class="inline-flex items-center px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md">
                                        Xem trang cửa hàng
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thông báo chào mừng -->
            <div class="mt-8 bg-blue-50 dark:bg-blue-900/20 rounded-lg p-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-medium text-blue-800 dark:text-blue-200">
                            Chào mừng đến với Hệ thống Quản lý Kho hàng
                        </h3>
                        <p class="mt-2 text-blue-700 dark:text-blue-300">
                            Bạn đã đăng nhập thành công! Sử dụng menu điều hướng để truy cập các chức năng quản lý kho hàng, cửa hàng và báo cáo.
                        </p>
                        <div class="mt-4">
                            <div class="flex flex-wrap gap-3">
                                <a href="{{ route('warehouses.index') }}" class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                    Quản lý Kho
                                </a>
                                <a href="{{ route('stores.index') }}" class="inline-flex items-center px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    Quản lý Cửa hàng
                                </a>
                                <a href="{{ route('purchase-orders.index') }}" class="inline-flex items-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                    </svg>
                                    Hóa đơn nhập
                                </a>
                                <a href="{{ route('categories.index') }}" class="inline-flex items-center px-3 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-md transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    Quản lý Danh mục
                                </a>
                                <a href="{{ route('products.index') }}" class="inline-flex items-center px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                    Quản lý Sản phẩm
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
