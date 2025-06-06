<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Quản lý Sản phẩm') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="w-9/12 mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">{{ __('Danh sách Sản phẩm') }}</h3>
                        <a href="{{ route('products.create') }}" 
                           class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-md transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Thêm sản phẩm
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
                        </div>                    @endif

                    @if($products->count() > 0)
                        <!-- Search Section -->
                        <div class="mb-6">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <input type="text" 
                                       id="searchInput" 
                                       class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md leading-5 bg-white dark:bg-gray-700 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-gray-100 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Tìm kiếm sản phẩm theo tên, SKU, danh mục hoặc mô tả...">
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
                            <div id="searchResults" class="hidden mt-2 text-sm text-gray-600 dark:text-gray-400">
                                <span id="searchResultsText"></span>
                            </div>
                        </div>

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
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Đơn vị
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Ngày tạo
                                        </th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Hành động
                                        </th>
                                    </tr>
                                </thead>                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($products as $product)
                                    <tr class="product-row hover:bg-gray-50 dark:hover:bg-gray-700" 
                                        data-name="{{ strtolower($product->name) }}" 
                                        data-sku="{{ strtolower($product->sku) }}" 
                                        data-category="{{ strtolower($product->category->name ?? '') }}" 
                                        data-description="{{ strtolower($product->description ?? '') }}" 
                                        data-search="{{ strtolower($product->name . ' ' . $product->sku . ' ' . ($product->category->name ?? '') . ' ' . ($product->description ?? '')) }}">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $product->name }}
                                            </div>
                                            @if($product->description)
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ Str::limit($product->description, 50) }}
                                            </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                            <span class="font-mono bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">
                                                {{ $product->sku }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($product->category)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                                    {{ $product->category->name }}
                                                </span>
                                            @else
                                                <span class="text-gray-400 text-sm">Chưa phân loại</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                            {{ $product->unit ?? 'Chưa cập nhật' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $product->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-2">
                                            <a href="{{ route('products.show', $product) }}" 
                                               class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                                Xem
                                            </a>
                                            <a href="{{ route('products.edit', $product) }}" 
                                               class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                Sửa
                                            </a>
                                            <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                                        onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">
                                                    Xóa
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach                                </tbody>
                            </table>
                        </div>

                        <!-- Empty Search State -->
                        <div id="emptySearchState" class="hidden text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Không tìm thấy sản phẩm nào</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Không có sản phẩm nào khớp với từ khóa tìm kiếm. Hãy thử từ khóa khác.</p>
                        </div>
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
            </div>        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const searchLoader = document.getElementById('searchLoader');
        const clearSearch = document.getElementById('clearSearch');
        const searchResults = document.getElementById('searchResults');
        const searchResultsText = document.getElementById('searchResultsText');
        const tableContainer = document.querySelector('.overflow-x-auto');
        const emptySearchState = document.getElementById('emptySearchState');
        
        let searchTimeout;
        let allProductRows = [];
        
        // Store all product rows
        if (tableContainer) {
            allProductRows = Array.from(tableContainer.querySelectorAll('.product-row'));
        }
        
        function showLoader() {
            if (searchLoader) searchLoader.classList.remove('hidden');
        }
        
        function hideLoader() {
            if (searchLoader) searchLoader.classList.add('hidden');
        }
        
        function updateClearButton() {
            if (clearSearch) {
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
                if (!tableContainer || allProductRows.length === 0) {
                    hideLoader();
                    return;
                }
                
                const searchTerms = query.toLowerCase().trim().split(/\s+/).filter(term => term.length > 0);
                let visibleCount = 0;
                
                if (searchTerms.length === 0) {
                    // Show all rows
                    allProductRows.forEach(row => {
                        row.style.display = '';
                        visibleCount++;
                    });
                    
                    if (emptySearchState) {
                        emptySearchState.style.display = 'none';
                    }
                    tableContainer.style.display = '';
                } else {
                    // Filter rows based on search terms
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
                    
                    // Handle empty state
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
        
        // Search input event handler with debounce
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const query = this.value;
                updateClearButton();
                
                // Clear previous timeout
                if (searchTimeout) {
                    clearTimeout(searchTimeout);
                }
                
                // Set new timeout for 300ms
                searchTimeout = setTimeout(() => {
                    performSearch(query);
                }, 300);
            });
        }
        
        // Clear search button
        if (clearSearch) {
            clearSearch.addEventListener('click', function() {
                searchInput.value = '';
                updateClearButton();
                performSearch('');
            });
        }
        
        // Handle search input focus and blur for better UX
        if (searchInput) {
            searchInput.addEventListener('focus', function() {
                this.parentElement.classList.add('ring-2', 'ring-blue-500', 'border-blue-500');
            });
            
            searchInput.addEventListener('blur', function() {
                this.parentElement.classList.remove('ring-2', 'ring-blue-500', 'border-blue-500');
            });
        }
        
        // Initialize
        updateClearButton();
    });
    </script>
</x-app-layout>
