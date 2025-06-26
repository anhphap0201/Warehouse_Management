<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Chi tiết Cửa hàng: ') . $store->name }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('stores.edit', $store) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-edit mr-2"></i>
                    Chỉnh sửa
                </a>
                <a href="{{ route('stores.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Quay lại
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="w-9/12 mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Store Information -->
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title mb-6">
                        <i class="fas fa-store mr-2"></i>Thông tin Cửa hàng
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="form-label">Tên Cửa hàng</label>
                            <div class="form-static-text">{{ $store->name }}</div>
                        </div>
                        
                        <div>
                            <label class="form-label">Trạng thái</label>
                            <div class="mt-1">
                                <span class="badge {{ $store->status ? 'badge-success' : 'badge-error' }}">
                                    {{ $store->status ? 'Hoạt động' : 'Ngừng hoạt động' }}
                                </span>
                            </div>
                        </div>
                        
                        <div>
                            <label class="form-label">Địa chỉ</label>
                            <div class="form-static-text">{{ $store->location ?? 'Chưa có địa chỉ' }}</div>
                        </div>
                        
                        <div>
                            <label class="form-label">Số điện thoại</label>
                            <div class="form-static-text">{{ $store->phone ?? 'Chưa có số điện thoại' }}</div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quản lý</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $store->manager ?? 'Chưa có quản lý' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ngày tạo</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $store->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Store Inventory -->
            <div class="card">
                <div class="card-body">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="card-title">
                            <i class="fas fa-boxes mr-2"></i>Tồn kho Cửa hàng
                        </h3>
                        @if($store->inventory && $store->inventory->count() > 0)
                            <div class="badge badge-info">
                                Tổng: {{ $store->inventory->count() }} loại sản phẩm
                            </div>
                        @endif
                    </div>

                    @if($store->inventory && $store->inventory->count() > 0)
                        <!-- Search Section -->
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
                                       placeholder="Tìm kiếm sản phẩm theo tên hoặc SKU...">
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
                        </div>
                        <div class="overflow-x-auto">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Sản phẩm</th>
                                        <th>Số lượng</th>
                                        <th>Tồn kho tối thiểu</th>
                                        <th>Tồn kho tối đa</th>
                                        <th>Trạng thái</th>
                                    </tr>
                                </thead>                                <tbody class="bg-transparent dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($store->inventory as $inventory)
                                        <tr data-product-name="{{ strtolower($inventory->product->name ?? '') }}"
                                            data-sku="{{ strtolower($inventory->product->sku ?? '') }}">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $inventory->product->name ?? 'N/A' }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    SKU: {{ $inventory->product->sku ?? 'N/A' }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900 dark:text-gray-100">
                                                    {{ number_format($inventory->quantity) }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900 dark:text-gray-100">
                                                    {{ number_format($inventory->min_stock) }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900 dark:text-gray-100">
                                                    {{ number_format($inventory->max_stock) }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($inventory->isLowStock())
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                        Tồn kho thấp
                                                    </span>
                                                @elseif($inventory->isOverstocked())
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        Tồn kho cao
                                                    </span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        Bình thường
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="text-gray-500 dark:text-gray-400 mb-4">
                                <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Chưa có tồn kho</h3>
                            <p class="text-gray-500 dark:text-gray-400 mb-4">Cửa hàng này chưa có sản phẩm nào trong kho</p>
                            <a href="{{ route('stores.receive.form', $store) }}" 
                               class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Nhận hàng đầu tiên
                            </a>
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
        const inventoryTable = document.querySelector('tbody');
        const emptyState = document.querySelector('.text-center.py-8');
        
        let searchTimeout;
        let allInventoryRows = [];
        let filteredRows = [];
          // Lưu trữ dữ liệu tồn kho gốc chỉ khi chúng ta có input tìm kiếm
        if (searchInput && inventoryTable) {
            allInventoryRows = Array.from(inventoryTable.querySelectorAll('.inventory-row')).map(row => {
                const productName = row.dataset.productName || '';
                const sku = row.dataset.sku || '';
                
                return {
                    element: row,
                    searchText: `${productName} ${sku}`.trim(),
                    productName: productName,
                    sku: sku
                };
            });
            filteredRows = [...allInventoryRows];
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
            if (query.trim() && searchResults && searchResultsText) {
                searchResults.classList.remove('hidden');
                if (resultCount === 0) {
                    searchResultsText.textContent = `Không tìm thấy kết quả nào cho "${query}"`;
                } else if (resultCount === totalCount) {
                    searchResults.classList.add('hidden');
                } else {
                    searchResultsText.textContent = `Tìm thấy ${resultCount} trong ${totalCount} sản phẩm cho "${query}"`;
                }
            } else if (searchResults) {
                searchResults.classList.add('hidden');
            }
        }
        
        function performSearch(query) {
            if (!searchInput || !inventoryTable) return;
            
            showLoader();
              // Mô phỏng tìm kiếm async với setTimeout
            setTimeout(() => {
                const searchTerms = query.toLowerCase().trim().split(/\s+/).filter(term => term.length > 0);
                
                if (searchTerms.length === 0) {
                    // Hiển thị tất cả dòng
                    filteredRows = [...allInventoryRows];
                    allInventoryRows.forEach(row => {
                        row.element.style.display = '';
                    });
                    
                    if (emptyState && emptyState.style.display !== 'none') {
                        // Đặt lại trạng thái rỗng về ban đầu nếu đã được sửa đổi bởi tìm kiếm
                        const title = emptyState.querySelector('h3');
                        if (title && title.textContent.includes('Không tìm thấy')) {
                            emptyState.style.display = 'none';
                            inventoryTable.parentElement.parentElement.style.display = '';
                        }
                    }
                } else {
                    // Lọc dòng dựa trên các từ khóa tìm kiếm
                    filteredRows = allInventoryRows.filter(row => {
                        return searchTerms.every(term => row.searchText.includes(term));
                    });
                    
                    // Hiển thị/ẩn dòng
                    allInventoryRows.forEach(row => {
                        const shouldShow = filteredRows.includes(row);
                        row.element.style.display = shouldShow ? '' : 'none';
                    });
                    
                    // Xử lý trạng thái rỗng
                    if (filteredRows.length === 0) {
                        if (emptyState) {
                            emptyState.style.display = '';
                            const title = emptyState.querySelector('h3');
                            const description = emptyState.querySelector('p');
                            if (title) title.textContent = 'Không tìm thấy sản phẩm nào';
                            if (description) description.textContent = `Không có sản phẩm nào khớp với từ khóa "${query}". Hãy thử từ khóa khác.`;
                        }
                        if (inventoryTable.parentElement.parentElement) {
                            inventoryTable.parentElement.parentElement.style.display = 'none';
                        }
                    } else {
                        if (emptyState && emptyState.style.display !== 'none') {
                            emptyState.style.display = 'none';
                        }
                        if (inventoryTable.parentElement.parentElement) {
                            inventoryTable.parentElement.parentElement.style.display = '';
                        }
                    }
                }
                
                updateSearchResults(query, filteredRows.length, allInventoryRows.length);
                hideLoader();
            }, 300);
        }
          // Xử lý sự kiện input tìm kiếm với debounce
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const query = this.value;
                updateClearButton();
                
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    performSearch(query);
                }, 300);
            });
        }
        
        // Nút xóa tìm kiếm
        if (clearSearch) {
            clearSearch.addEventListener('click', function() {
                if (searchInput) {
                    searchInput.value = '';
                    updateClearButton();
                    performSearch('');
                }
            });        }
        
        // Khởi tạo
        updateClearButton();
    });
    </script>
</x-app-layout>
