<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Quản lý Sản phẩm') }}
        </h2>
    </x-slot>

    <div class="py-4 sm:py-6">
        <div class="container-70">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 space-y-4 sm:space-y-0">
                        <h3 class="text-lg font-semibold">{{ __('Danh sách Sản phẩm') }}</h3>
                        <a href="{{ route('products.create') }}" 
                           class="touch-target inline-flex items-center justify-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-md transition-colors w-full sm:w-auto">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            {{ __('Thêm Sản phẩm') }}
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($products->count() > 0)
                        <!-- Search Section -->
                        <x-form-section class="mb-6">
                            <div class="form-grid-responsive">
                                <div class="col-span-full">
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                        </div>
                                        <x-text-input 
                                            type="text" 
                                            id="searchInput" 
                                            class="w-full pl-10 pr-10"
                                            placeholder="Tìm kiếm sản phẩm theo tên, SKU, danh mục hoặc mô tả..."
                                            size="sm" />
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center space-x-2">
                                            <div id="searchLoader" class="hidden">
                                                <svg class="animate-spin h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                            </div>
                                            <button type="button" id="clearSearch" class="touch-target hidden text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Search Results Summary -->
                            <div id="searchResults" class="hidden mt-4">
                                <div class="bg-blue-50 dark:bg-blue-900/50 border border-blue-200 dark:border-blue-700 rounded-md p-3">
                                    <div class="flex items-center">
                                        <svg class="h-5 w-5 text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span id="searchResultsText" class="text-sm text-blue-700 dark:text-blue-300"></span>
                                    </div>
                                </div>
                            </div>
                        </x-form-section>

                        <!-- Responsive Products Table -->
                        <x-table class="table-responsive" :mobileCards="true" id="productsTable">
                            <!-- Table Headers -->
                            <x-table-header icon="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" iconColor="purple-500">
                                Sản phẩm
                            </x-table-header>
                            <x-table-header class="hidden md:table-cell" icon="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" iconColor="blue-500">
                                SKU
                            </x-table-header>
                            <x-table-header class="hidden lg:table-cell" icon="M7 7h.01M7 3h5c.512 0 .853.174 1.13.453l4.586 4.586A2 2 0 0018 9v6a2 2 0 01-2 2H6a2 2 0 01-2-2V5a2 2 0 012-2z" iconColor="indigo-500">
                                Danh mục
                            </x-table-header>
                            <x-table-header class="hidden lg:table-cell" icon="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" iconColor="green-500">
                                Đơn vị
                            </x-table-header>
                            <x-table-header class="hidden lg:table-cell" icon="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" iconColor="red-500">
                                Ngày tạo
                            </x-table-header>
                            <x-table-header class="text-center" icon="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" iconColor="gray-500">
                                Hành động
                            </x-table-header>

                            <!-- Table Body -->
                            @forelse($products as $product)
                                <!-- Desktop Row -->
                                <tr class="hidden md:table-row product-row hover:bg-gray-50 dark:hover:bg-gray-700" 
                                    data-name="{{ strtolower($product->name) }}" 
                                    data-sku="{{ strtolower($product->sku) }}" 
                                    data-category="{{ strtolower($product->category->name ?? '') }}" 
                                    data-description="{{ strtolower($product->description ?? '') }}" 
                                    data-search="{{ strtolower($product->name . ' ' . $product->sku . ' ' . ($product->category->name ?? '') . ' ' . ($product->description ?? '')) }}">
                                    
                                    <x-table-cell>
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8">
                                                <div class="h-8 w-8 rounded-lg bg-gradient-to-br from-purple-100 to-purple-200 dark:from-purple-900 dark:to-purple-800 flex items-center justify-center">
                                                    <svg class="h-4 w-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $product->name }}
                                                </div>
                                                @if($product->description)
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ Str::limit($product->description, 50) }}
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </x-table-cell>
                                    <x-table-cell class="hidden md:table-cell">
                                        <span class="font-mono bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded text-sm">
                                            {{ $product->sku }}
                                        </span>
                                    </x-table-cell>
                                    <x-table-cell class="hidden lg:table-cell">
                                        @if($product->category)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                                {{ $product->category->name }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 text-sm">Chưa phân loại</span>
                                        @endif
                                    </x-table-cell>
                                    <x-table-cell class="hidden lg:table-cell">
                                        <span class="text-sm text-gray-900 dark:text-gray-300">
                                            {{ $product->unit ?? 'Chưa cập nhật' }}
                                        </span>
                                    </x-table-cell>
                                    <x-table-cell class="hidden lg:table-cell">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <span class="text-sm text-gray-900 dark:text-gray-100">{{ $product->created_at->format('d/m/Y H:i') }}</span>
                                        </div>
                                    </x-table-cell>
                                    <x-table-cell class="text-center">
                                        <div class="flex items-center justify-center space-x-2">
                                            <a href="{{ route('products.show', $product) }}" 
                                               class="touch-target inline-flex items-center px-2 py-1 border border-blue-300 rounded-md text-blue-600 hover:text-blue-900 hover:bg-blue-50 dark:text-blue-400 dark:hover:text-blue-300 dark:border-blue-600 dark:hover:bg-blue-900 transition-all duration-200">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                Xem chi tiết
                                            </a>
                                        </div>
                                    </x-table-cell>
                                </tr>

                                <!-- Mobile Card View -->
                                <x-table-mobile-card class="md:hidden product-row-mobile" 
                                    data-name="{{ strtolower($product->name) }}" 
                                    data-sku="{{ strtolower($product->sku) }}" 
                                    data-category="{{ strtolower($product->category->name ?? '') }}" 
                                    data-description="{{ strtolower($product->description ?? '') }}" 
                                    data-search="{{ strtolower($product->name . ' ' . $product->sku . ' ' . ($product->category->name ?? '') . ' ' . ($product->description ?? '')) }}">
                                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md border border-gray-200 dark:border-gray-700 p-4 mb-4">
                                        <!-- Card Header -->
                                        <div class="flex items-center justify-between mb-3">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-8 w-8">
                                                    <div class="h-8 w-8 rounded-lg bg-gradient-to-br from-purple-100 to-purple-200 dark:from-purple-900 dark:to-purple-800 flex items-center justify-center">
                                                        <svg class="h-4 w-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div class="ml-3">
                                                    <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                                        {{ $product->name }}
                                                    </h4>
                                                    @if($product->category)
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200 mt-1">
                                                            {{ $product->category->name }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Card Content -->
                                        <div class="space-y-2 text-sm text-gray-600 dark:text-gray-400 mb-4">
                                            <div class="flex justify-between">
                                                <span class="font-medium">SKU:</span>
                                                <span class="font-mono bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded text-xs">{{ $product->sku }}</span>
                                            </div>
                                            @if($product->unit)
                                                <div class="flex justify-between">
                                                    <span class="font-medium">Đơn vị:</span>
                                                    <span>{{ $product->unit }}</span>
                                                </div>
                                            @endif
                                            <div class="flex justify-between">
                                                <span class="font-medium">Ngày tạo:</span>
                                                <span>{{ $product->created_at->format('d/m/Y') }}</span>
                                            </div>
                                            @if($product->description)
                                                <div class="pt-2">
                                                    <span class="font-medium">Mô tả:</span>
                                                    <p class="text-gray-500 dark:text-gray-400 text-xs mt-1">{{ Str::limit($product->description, 100) }}</p>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Card Actions -->
                                        <div class="flex space-x-2">
                                            <a href="{{ route('products.show', $product) }}" 
                                               class="touch-target flex-1 bg-blue-500 hover:bg-blue-600 text-white text-center py-2 px-3 rounded text-sm font-medium transition-colors">
                                                <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                Xem chi tiết
                                            </a>
                                            <a href="{{ route('products.edit', $product) }}" 
                                               class="touch-target bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-3 rounded text-sm font-medium transition-colors">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </x-table-mobile-card>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center justify-center space-y-4">
                                            <div class="w-16 h-16 bg-gradient-to-br from-purple-100 to-blue-100 dark:from-gray-700 dark:to-gray-600 rounded-full flex items-center justify-center">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                </svg>
                                            </div>
                                            <div class="text-center">
                                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Không tìm thấy sản phẩm nào</h3>
                                                <p class="text-gray-500 dark:text-gray-400 mb-4">Không có sản phẩm nào khớp với tiêu chí tìm kiếm của bạn.</p>
                                                <button onclick="clearFilters()" 
                                                        class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-md transition-colors">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                    </svg>
                                                    Xóa bộ lọc
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </x-table>

                        <!-- Empty Search State -->
                        <div id="emptySearchState" class="hidden text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Không tìm thấy sản phẩm nào</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Không có sản phẩm nào khớp với từ khóa tìm kiếm. Hãy thử từ khóa khác.</p>
                        </div>

                        <!-- Pagination -->
                        @if($products->hasPages())
                            <div class="mt-6">
                                {{ $products->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Chưa có sản phẩm</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Bắt đầu bằng cách tạo sản phẩm mới.</p>
                            <div class="mt-6">
                                <a href="{{ route('products.create') }}" 
                                   class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-md">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Thêm sản phẩm đầu tiên
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const searchLoader = document.getElementById('searchLoader');
        const clearSearch = document.getElementById('clearSearch');
        const searchResults = document.getElementById('searchResults');
        const searchResultsText = document.getElementById('searchResultsText');
        const tableContainer = document.getElementById('productsTable');
        const emptySearchState = document.getElementById('emptySearchState');
        
        let searchTimeout;
        let allProductRows = [];
        let allProductMobileCards = [];        
        // Lưu trữ tất cả dòng sản phẩm và thẻ di động
        if (tableContainer) {
            allProductRows = Array.from(tableContainer.querySelectorAll('.product-row'));
            allProductMobileCards = Array.from(tableContainer.querySelectorAll('.product-row-mobile'));
        }
        
        function showLoader() {
            if (searchLoader) searchLoader.classList.remove('hidden');
        }
        
        function hideLoader() {
            if (searchLoader) searchLoader.classList.add('hidden');
        }
        
        function updateClearButton() {
            if (clearSearch && searchInput) {
                if (searchInput.value.trim()) {
                    clearSearch.classList.remove('hidden');
                } else {
                    clearSearch.classList.add('hidden');
                }
            }
        }
        
        function updateSearchResults(query, resultCount, totalCount) {
            if (!searchResults || !searchResultsText) return;
            
            if (query.trim()) {
                searchResults.classList.remove('hidden');
                if (resultCount === 0) {
                    searchResultsText.textContent = `Không tìm thấy kết quả nào cho "${query}"`;
                } else if (resultCount === totalCount) {
                    searchResults.classList.add('hidden');
                } else {
                    searchResultsText.textContent = `Tìm thấy ${resultCount} trong ${totalCount} sản phẩm cho "${query}"`;
                }
            } else {
                searchResults.classList.add('hidden');
            }
        }
        
        function performSearch(query) {
            showLoader();
            
            setTimeout(() => {
                if (!tableContainer || (allProductRows.length === 0 && allProductMobileCards.length === 0)) {
                    hideLoader();
                    return;
                }
                
                const searchTerms = query.toLowerCase().trim().split(/\s+/).filter(term => term.length > 0);
                let visibleCount = 0;
                
                if (searchTerms.length === 0) {                    // Hiển thị tất cả dòng và thẻ
                    allProductRows.forEach(row => {
                        row.style.display = '';
                        visibleCount++;
                    });
                    allProductMobileCards.forEach(card => {
                        card.style.display = '';
                    });
                    
                    if (emptySearchState) {
                        emptySearchState.style.display = 'none';
                    }
                    tableContainer.style.display = '';
                } else {                    // Lọc dòng và thẻ dựa trên các từ khóa tìm kiếm
                    allProductRows.forEach(row => {
                        const searchText = row.getAttribute('data-search') || '';
                        const shouldShow = searchTerms.every(term => searchText.includes(term));
                        
                        if (shouldShow) {
                            row.style.display = '';
                            visibleCount++;
                        } else {
                            row.style.display = 'none';
                        }
                    });
                    
                    allProductMobileCards.forEach(card => {
                        const searchText = card.getAttribute('data-search') || '';
                        const shouldShow = searchTerms.every(term => searchText.includes(term));
                        
                        if (shouldShow) {
                            card.style.display = '';
                        } else {
                            card.style.display = 'none';
                        }
                    });                    
                    // Xử lý trạng thái rỗng
                    if (visibleCount === 0) {
                        if (emptySearchState) {
                            emptySearchState.style.display = '';
                        }
                        tableContainer.style.display = 'none';
                    } else {
                        if (emptySearchState) {
                            emptySearchState.style.display = 'none';
                        }
                        tableContainer.style.display = '';
                    }
                }
                
                updateSearchResults(query, visibleCount, allProductRows.length);
                hideLoader();
            }, 100);
        }        
        // Xử lý sự kiện input tìm kiếm với debounce
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const query = this.value;
                updateClearButton();                
                // Xóa timeout trước đó
                if (searchTimeout) {
                    clearTimeout(searchTimeout);
                }
                
                // Đặt timeout mới cho 300ms
                searchTimeout = setTimeout(() => {
                    performSearch(query);
                }, 300);
            });
        }        
        // Nút xóa tìm kiếm
        if (clearSearch) {
            clearSearch.addEventListener('click', function() {
                searchInput.value = '';
                updateClearButton();
                performSearch('');
            });
        }        
        // Xử lý focus và blur của input tìm kiếm để cải thiện UX
        if (searchInput) {
            searchInput.addEventListener('focus', function() {
                this.parentElement.classList.add('ring-2', 'ring-blue-500', 'border-blue-500');
            });
            
            searchInput.addEventListener('blur', function() {
                this.parentElement.classList.remove('ring-2', 'ring-blue-500', 'border-blue-500');
            });
        }        
        // Khởi tạo
        updateClearButton();
    });    
    // Hàm toàn cục để xóa bộ lọc
    function clearFilters() {
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.value = '';
            searchInput.dispatchEvent(new Event('input'));
        }
    }
    </script>
</x-app-layout>
