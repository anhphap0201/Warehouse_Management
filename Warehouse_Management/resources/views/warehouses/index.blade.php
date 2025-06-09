<x-app-layout>    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Quản lý Kho Hàng') }}
            </h2>
            <a href="{{ route('warehouses.create') }}" 
               class="touch-target inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors w-full sm:w-auto">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                {{ __('Thêm Kho Mới') }}
            </a>
        </div>
    </x-slot><div class="py-4 sm:py-6">
        <div class="container-70">
            <!-- Success Message -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif            <!-- Warehouses Grid -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900 dark:text-gray-100">                    @if($warehouses->count() > 0)
                        <!-- Responsive Search Section -->
                        <x-form-section class="mb-6" padding="responsive">
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
                                    placeholder="Tìm kiếm kho hàng theo tên hoặc địa chỉ..."
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

                        <div id="warehousesGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">                            @foreach($warehouses as $warehouse)
                                <div class="warehouse-card bg-gray-50 dark:bg-gray-700 rounded-lg p-6 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors border border-gray-200 dark:border-gray-600" 
                                     data-name="{{ strtolower($warehouse->name) }}" 
                                     data-location="{{ strtolower($warehouse->location ?? '') }}" 
                                     data-search="{{ strtolower($warehouse->name . ' ' . ($warehouse->location ?? '')) }}">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center mb-3">
                                                <svg class="w-8 h-8 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                </svg>
                                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                                    {{ $warehouse->name }}
                                                </h3>
                                            </div>
                                            
                                            @if($warehouse->location)
                                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400 mb-3">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    </svg>
                                                    {{ $warehouse->location }}
                                                </div>
                                            @endif

                                            <!-- Warehouse Stats -->
                                            <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400 mb-4">
                                                <span class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                                    </svg>
                                                    {{ $warehouse->inventory->count() }} sản phẩm
                                                </span>
                                                <span class="text-xs">
                                                    {{ $warehouse->created_at->format('d/m/Y') }}
                                                </span>
                                            </div>                            <!-- Action Buttons -->
                            <div class="flex space-x-2">
                                <a href="{{ route('warehouses.show', $warehouse) }}" 
                                   class="touch-target flex-1 bg-blue-500 hover:bg-blue-600 text-white text-center py-2 px-3 rounded text-sm font-medium transition-colors">
                                    {{ __('Xem Chi Tiết') }}
                                </a>
                                <a href="{{ route('warehouses.edit', $warehouse) }}" 
                                   class="touch-target bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-3 rounded text-sm font-medium transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <form action="{{ route('warehouses.destroy', $warehouse) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            onclick="return confirm('Bạn có chắc chắn muốn xóa kho này?')"
                                            class="touch-target bg-red-500 hover:bg-red-600 text-white py-2 px-3 rounded text-sm font-medium transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                                        </div>
                                    </div>                                </div>
                            @endforeach
                        </div>

                        <!-- Empty Search State -->
                        <div id="emptySearchState" class="hidden text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Không tìm thấy kho hàng nào</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Không có kho hàng nào khớp với từ khóa tìm kiếm. Hãy thử từ khóa khác.</p>
                        </div>

                        <!-- Pagination -->
                        @if(method_exists($warehouses, 'links'))
                            <div class="mt-6">
                                {{ $warehouses->links() }}
                            </div>
                        @endif
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-12">
                            <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <h3 class="mt-6 text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Chưa có kho hàng nào') }}</h3>
                            <p class="mt-2 text-gray-500 dark:text-gray-400">{{ __('Bắt đầu bằng cách tạo kho hàng đầu tiên của bạn.') }}</p>                            <div class="mt-6">
                                <a href="{{ route('warehouses.create') }}" 
                                   class="touch-target inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white text-sm font-medium rounded-lg shadow-lg transform transition-all duration-200 hover:scale-105">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    {{ __('Thêm Kho Đầu Tiên') }}
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
        const warehousesGrid = document.getElementById('warehousesGrid');
        const emptySearchState = document.getElementById('emptySearchState');
        
        let searchTimeout;
        let allWarehouseCards = [];
        
        // Lưu trữ tất cả thẻ kho hàng
        if (warehousesGrid) {
            allWarehouseCards = Array.from(warehousesGrid.querySelectorAll('.warehouse-card'));
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
                    searchResultsText.textContent = `Tìm thấy ${resultCount} trong ${totalCount} kho hàng cho "${query}"`;
                }
            } else {
                searchResults.classList.add('hidden');
            }
        }
        
        function performSearch(query) {
            showLoader();
            
            setTimeout(() => {
                if (!warehousesGrid || allWarehouseCards.length === 0) {
                    hideLoader();
                    return;
                }
                
                const searchTerms = query.toLowerCase().trim().split(/\s+/).filter(term => term.length > 0);
                let visibleCount = 0;
                
                if (searchTerms.length === 0) {
                    // Hiển thị tất cả thẻ
                    allWarehouseCards.forEach(card => {
                        card.style.display = '';
                        visibleCount++;
                    });
                    
                    if (emptySearchState) {
                        emptySearchState.style.display = 'none';
                    }
                    warehousesGrid.style.display = '';
                } else {
                    // Lọc thẻ dựa trên các từ khóa tìm kiếm
                    allWarehouseCards.forEach(card => {
                        const searchText = card.getAttribute('data-search') || '';
                        const shouldShow = searchTerms.every(term => searchText.includes(term));
                        
                        if (shouldShow) {
                            card.style.display = '';
                            visibleCount++;
                        } else {
                            card.style.display = 'none';
                        }
                    });
                    
                    // Xử lý trạng thái rỗng
                    if (visibleCount === 0) {
                        if (emptySearchState) {
                            emptySearchState.style.display = '';
                        }
                        warehousesGrid.style.display = 'none';
                    } else {
                        if (emptySearchState) {
                            emptySearchState.style.display = 'none';
                        }
                        warehousesGrid.style.display = '';
                    }
                }
                
                updateSearchResults(query, visibleCount, allWarehouseCards.length);
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
    </script>
</x-app-layout>
