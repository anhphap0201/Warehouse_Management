<x-app-layout>    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Chi Tiết Kho Hàng') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('warehouses.edit', $warehouse) }}" 
                   class="btn btn-primary btn-sm">
                    <i class="fas fa-edit mr-2"></i>
                    Chỉnh sửa
                </a>
                <a href="{{ route('warehouses.index') }}" 
                   class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Quay lại
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="w-9/12 mx-auto sm:px-6 lg:px-8">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title mb-6">{{ __('Thông tin Kho Hàng') }}</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Tên Kho</h4>
                            <p class="text-lg font-semibold">{{ $warehouse->name }}</p>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Địa Chỉ</h4>
                            <p class="text-lg">{{ $warehouse->location ?? 'Chưa có thông tin' }}</p>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Ngày Tạo</h4>
                            <p class="text-lg">{{ $warehouse->created_at->format('d/m/Y H:i') }}</p>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Cập Nhật Lần Cuối</h4>
                            <p class="text-lg">{{ $warehouse->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    <!-- Actions Section -->
                    <div class="mt-8 border-t dark:border-gray-700 pt-6">
                        <h4 class="text-lg font-semibold mb-4">Thao Tác</h4>
                        <div class="flex space-x-4">
                            <a href="{{ route('warehouses.edit', $warehouse) }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Chỉnh Sửa Kho
                            </a>
                            
                            <form method="POST" action="{{ route('warehouses.destroy', $warehouse) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition-colors"
                                        onclick="return confirm('Bạn có chắc chắn muốn xóa kho này không?')">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Xóa Kho
                                </button>
                            </form>                    </div>
                </div>
            </div>

            <!-- Warehouse Inventory Section -->
            <div class="mt-8 bg-transparent dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">Tồn kho trong Kho hàng</h3>
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            Tổng: {{ $warehouse->inventory->count() }} loại sản phẩm
                        </div>
                    </div>                    <!-- Search and Filter Section -->
                    <div class="mb-6 space-y-4">
                        <!-- Search Input -->
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" 
                                   id="searchInput" 
                                   class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md leading-5 bg-transparent dark:bg-gray-700 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-gray-100 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Tìm kiếm sản phẩm theo tên, SKU, mô tả hoặc danh mục...">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <div id="searchLoader" class="hidden">
                                    <svg class="animate-spin h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </div>
                                <button id="clearSearch" class="hidden text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Search Results Summary -->
                        <div id="searchResults" class="hidden text-sm text-gray-600 dark:text-gray-400">
                            <span id="searchResultsText"></span>
                        </div>

                        <!-- Category Filter -->
                        @if($categories->count() > 0)
                        <div>
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('warehouses.show', $warehouse) }}" 
                                   class="inline-flex items-center px-3 py-2 rounded-md text-sm font-medium transition-colors {{ !$categoryFilter ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                                </svg>
                                Tất cả
                                @if(!$categoryFilter)
                                    <span class="ml-2 bg-blue-200 dark:bg-blue-800 text-blue-800 dark:text-blue-200 text-xs px-2 py-1 rounded-full">
                                        {{ $warehouse->inventory->count() }}
                                    </span>
                                @endif
                            </a>
                            
                            @foreach($categories as $category)
                            <a href="{{ route('warehouses.show', [$warehouse, 'category_id' => $category->id]) }}" 
                               class="inline-flex items-center px-3 py-2 rounded-md text-sm font-medium transition-colors {{ $categoryFilter == $category->id ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                                {{ $category->name }}
                                <span class="ml-2 bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-300 text-xs px-2 py-1 rounded-full">
                                    {{ $warehouse->inventory->where('product.category_id', $category->id)->count() }}
                                </span>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    </div>

                    <!-- Inventory Table -->
                    @if($filteredInventory->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Sản phẩm
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            SKU
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Danh mục
                                        </th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Số lượng
                                        </th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Hành động
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-transparent dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($filteredInventory as $inventory)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $inventory->product->name }}
                                            </div>
                                            @if($inventory->product->description)
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ Str::limit($inventory->product->description, 50) }}
                                            </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="font-mono bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded text-sm">
                                                {{ $inventory->product->sku }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($inventory->product->category)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                                    {{ $inventory->product->category->name }}
                                                </span>
                                            @else
                                                <span class="text-gray-400 text-sm">Chưa phân loại</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium {{ $inventory->quantity > 0 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                                {{ number_format($inventory->quantity) }} {{ $inventory->product->unit ?? '' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <a href="{{ route('products.show', $inventory->product) }}" 
                                               class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                                Xem chi tiết
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <td colspan="3" class="px-6 py-3 text-left text-sm font-medium text-gray-900 dark:text-gray-100">
                                            @if($categoryFilter)
                                                Tổng trong danh mục:
                                            @else
                                                Tổng tồn kho:
                                            @endif
                                        </td>
                                        <td class="px-6 py-3 text-right text-sm font-bold">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                {{ number_format($filteredInventory->sum('quantity')) }} sản phẩm
                                            </span>
                                        </td>
                                        <td class="px-6 py-3"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                @if($categoryFilter)
                                    Không có sản phẩm nào trong danh mục này
                                @else
                                    Kho hàng chưa có sản phẩm nào
                                @endif
                            </h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                @if($categoryFilter)
                                    Hãy thử lọc theo danh mục khác hoặc xem tất cả sản phẩm.
                                @else
                                    Bắt đầu bằng cách thêm sản phẩm vào kho hàng này.
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>        </div>
    </div>

    @push('scripts')
    @vite(['resources/js/warehouse-show.js'])
    @endpush
</x-app-layout>
