<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Quản lý Cửa hàng') }}
            </h2>
            <a href="{{ route('stores.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Thêm Cửa hàng Mới
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    {{ session('error') }}
                </div>
            @endif            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if($stores->count() > 0)
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
                                       placeholder="Tìm kiếm cửa hàng theo tên, địa chỉ, SĐT hoặc quản lý...">
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
                        
                        <!-- Empty Search State -->
                        <div id="emptySearchState" class="hidden text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Không tìm thấy cửa hàng nào</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Không có cửa hàng nào khớp với từ khóa tìm kiếm. Hãy thử từ khóa khác.</p>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full table-auto">
                                <thead>
                                    <tr class="bg-gray-50 dark:bg-gray-700">
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Tên Cửa hàng
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Địa chỉ
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Số điện thoại
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Quản lý
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Trạng thái
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Hành động
                                        </th>
                                    </tr>
                                </thead>                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($stores as $store)
                                        <tr class="store-row" 
                                            data-name="{{ strtolower($store->name) }}"
                                            data-location="{{ strtolower($store->location ?? '') }}"
                                            data-phone="{{ strtolower($store->phone ?? '') }}"
                                            data-manager="{{ strtolower($store->manager ?? '') }}"
                                            data-status="{{ $store->status ? 'hoạt động' : 'ngừng hoạt động' }}">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $store->name }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900 dark:text-gray-100">
                                                    {{ $store->location ?? 'Chưa có địa chỉ' }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900 dark:text-gray-100">
                                                    {{ $store->phone ?? 'Chưa có SĐT' }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900 dark:text-gray-100">
                                                    {{ $store->manager ?? 'Chưa có quản lý' }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    {{ $store->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $store->status ? 'Hoạt động' : 'Ngừng hoạt động' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('stores.show', $store) }}" 
                                                       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded text-xs">
                                                        Xem
                                                    </a>
                                                    <a href="{{ route('stores.edit', $store) }}" 
                                                       class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-2 rounded text-xs">
                                                        Sửa
                                                    </a>
                                                    <form action="{{ route('stores.destroy', $store) }}" method="POST" 
                                                          class="inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa cửa hàng này?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded text-xs">
                                                            Xóa
                                                        </button>
                                                    </form>
                                                </div>
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Chưa có cửa hàng nào</h3>
                            <p class="text-gray-500 dark:text-gray-400 mb-4">Hãy thêm cửa hàng đầu tiên của bạn</p>
                            <a href="{{ route('stores.create') }}" 
                               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Thêm Cửa hàng Mới
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
        const tableContainer = document.querySelector('.overflow-x-auto');
        const emptySearchState = document.getElementById('emptySearchState');
        
        let searchTimeout;
        let allStoreRows = [];
        
        // Store all store rows
        if (tableContainer) {
            allStoreRows = Array.from(tableContainer.querySelectorAll('.store-row'));
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
                    searchResultsText.textContent = `Tìm thấy ${resultCount} trong ${totalCount} cửa hàng cho "${query}"`;
                }
            } else {
                searchResults.classList.add('hidden');
            }
        }
        
        function performSearch(query) {
            showLoader();
            
            setTimeout(() => {
                if (!tableContainer || allStoreRows.length === 0) {
                    hideLoader();
                    return;
                }
                
                const searchTerms = query.toLowerCase().trim().split(/\s+/).filter(term => term.length > 0);
                let visibleCount = 0;
                
                if (searchTerms.length === 0) {
                    // Show all rows
                    allStoreRows.forEach(row => {
                        row.style.display = '';
                        visibleCount++;
                    });
                    
                    if (emptySearchState) {
                        emptySearchState.style.display = 'none';
                    }
                    tableContainer.style.display = '';
                } else {
                    // Filter rows based on search terms
                    allStoreRows.forEach(row => {
                        const name = row.dataset.name || '';
                        const location = row.dataset.location || '';
                        const phone = row.dataset.phone || '';
                        const manager = row.dataset.manager || '';
                        const status = row.dataset.status || '';
                        
                        const searchText = `${name} ${location} ${phone} ${manager} ${status}`.trim();
                        const matches = searchTerms.every(term => searchText.includes(term));
                        
                        if (matches) {
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
                
                updateSearchResults(query, visibleCount, allStoreRows.length);
                hideLoader();
            }, 300);
        }
        
        // Search input event handler with debounce
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
        
        // Clear search button
        if (clearSearch) {
            clearSearch.addEventListener('click', function() {
                searchInput.value = '';
                updateClearButton();
                performSearch('');
            });
        }
        
        // Initialize
        updateClearButton();
    });
    </script>
</x-app-layout>
