<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="page-title flex items-center">
                    <i class="fas fa-box mr-3 text-blue-600"></i>
                    {{ __('Quản lý Sản phẩm') }}
                </h1>
                <p class="page-subtitle">Quản lý danh sách sản phẩm và thông tin chi tiết</p>
            </div>
            <a href="{{ route('products.create') }}" class="btn btn-primary">
                <i class="fas fa-plus mr-2"></i>
                {{ __('Thêm Sản phẩm') }}
            </a>
        </div>
    </x-slot>

    <!-- Search and Filter Section -->
    <div class="card mb-6">
        <div class="card-body">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" 
                               id="searchInput" 
                               class="form-input pl-10"
                               placeholder="Tìm kiếm sản phẩm theo tên, SKU, hoặc danh mục..." />
                    </div>
                </div>
                <div class="flex gap-2">
                    <select class="form-select" id="categoryFilter">
                        <option value="">Tất cả danh mục</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <button type="button" class="btn btn-outline" id="clearFilters">
                        <i class="fas fa-times mr-1"></i>
                        Xóa bộ lọc
                    </button>
                </div>
            </div>
        </div>
    </div>

    @if($products->count() > 0)
        <!-- Products Grid/Table -->
        <div class="mb-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">
                    Danh sách sản phẩm ({{ $products->total() }})
                </h3>
                <div class="flex items-center space-x-2">
                    <button type="button" class="btn btn-sm btn-outline" id="toggleView" data-view="table">
                        <i class="fas fa-th-large mr-1"></i>
                        Dạng lưới
                    </button>
                    <button type="button" class="btn btn-sm btn-outline" onclick="window.print()">
                        <i class="fas fa-print mr-1"></i>
                        In
                    </button>
                </div>
            </div>
            <!-- Table View -->
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>SKU</th>
                        <th>Danh mục</th>
                        <th>Đơn vị</th>
                        <th>Tồn kho</th>
                        <th>Ngày tạo</th>
                        <th class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr class="product-row" 
                            data-name="{{ strtolower($product->name) }}" 
                            data-sku="{{ strtolower($product->sku) }}" 
                            data-category="{{ strtolower($product->category->name ?? '') }}" 
                            data-category-id="{{ $product->category_id ?? '' }}"
                            data-search="{{ strtolower($product->name . ' ' . $product->sku . ' ' . ($product->category->name ?? '')) }}">
                            <td>
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-box text-blue-600"></i>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $product->name }}</div>
                                        @if($product->description)
                                            <div class="text-sm text-gray-500">{{ Str::limit($product->description, 50) }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $product->sku }}
                                </span>
                            </td>
                            <td>
                                @if($product->category)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $product->category->name }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-sm">Chưa phân loại</span>
                                @endif
                            </td>
                            <td>
                                <span class="text-sm text-gray-900">{{ $product->unit }}</span>
                            </td>
                            <td>
                                @php
                                    $totalInventory = $product->inventory->sum('quantity');
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $totalInventory > 10 ? 'bg-green-100 text-green-800' : ($totalInventory > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ $totalInventory }} {{ $product->unit }}
                                </span>
                            </td>
                            <td>
                                <span class="text-sm text-gray-500">{{ $product->created_at->format('d/m/Y') }}</span>
                            </td>
                            <td class="text-center">
                                <div class="flex items-center justify-center space-x-1">
                                    <a href="{{ route('products.show', $product) }}" 
                                       class="btn btn-sm btn-outline"
                                       title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('products.edit', $product) }}" 
                                       class="btn btn-sm btn-outline"
                                       title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('products.destroy', $product) }}" 
                                          method="POST" 
                                          style="display: inline;"
                                          onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-danger"
                                                title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-search text-gray-300 text-4xl mb-4"></i>
                                    <p class="text-gray-500">Không tìm thấy sản phẩm nào</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
                
                <!-- Grid View (Hidden by default) -->
                <div id="gridView" class="hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
                    @foreach($products as $product)
                        <div class="product-card border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow"
                             data-name="{{ strtolower($product->name) }}" 
                             data-sku="{{ strtolower($product->sku) }}" 
                             data-category="{{ strtolower($product->category->name ?? '') }}" 
                             data-category-id="{{ $product->category_id ?? '' }}"
                             data-search="{{ strtolower($product->name . ' ' . $product->sku . ' ' . ($product->category->name ?? '')) }}">
                            <div class="flex items-start justify-between mb-3">
                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-box text-blue-600"></i>
                                </div>
                                <div class="flex space-x-1">
                                    <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </div>
                            <h4 class="font-semibold text-gray-900 mb-2">{{ $product->name }}</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-500">SKU:</span>
                                    <span class="font-mono bg-gray-100 px-2 py-0.5 rounded text-xs">{{ $product->sku }}</span>
                                </div>
                                @if($product->category)
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Danh mục:</span>
                                        <span class="text-blue-600">{{ $product->category->name }}</span>
                                    </div>
                                @endif
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Đơn vị:</span>
                                    <span>{{ $product->unit }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Tồn kho:</span>
                                    @php $totalInventory = $product->inventory->sum('quantity'); @endphp
                                    <span class="px-2 py-0.5 rounded text-xs 
                                        {{ $totalInventory > 10 ? 'bg-green-100 text-green-800' : ($totalInventory > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ $totalInventory }} {{ $product->unit }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
            <div class="mt-6">
                {{ $products->links() }}
            </div>
        @endif

    @else
        <!-- Empty State -->
        <div class="card">
            <div class="card-body">
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-box text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Chưa có sản phẩm nào</h3>
                    <p class="text-gray-500 mb-6">Bắt đầu bằng cách thêm sản phẩm đầu tiên của bạn</p>
                    <a href="{{ route('products.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus mr-2"></i>
                        Thêm sản phẩm đầu tiên
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Search Results -->
    <div id="searchResults" class="hidden">
        <div class="alert alert-info">
            <i class="fas fa-info-circle mr-2"></i>
            <span id="searchResultsText"></span>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const categoryFilter = document.getElementById('categoryFilter');
            const clearFilters = document.getElementById('clearFilters');
            const toggleView = document.getElementById('toggleView');
            const tableView = document.getElementById('tableView');
            const gridView = document.getElementById('gridView');
            const searchResults = document.getElementById('searchResults');
            const searchResultsText = document.getElementById('searchResultsText');

            let currentView = 'table';

            // Search functionality
            function performSearch() {
                const searchTerm = searchInput.value.toLowerCase().trim();
                const selectedCategory = categoryFilter.value;
                const rows = document.querySelectorAll('.product-row');
                const cards = document.querySelectorAll('.product-card');
                let visibleCount = 0;

                function filterItem(element) {
                    const searchData = element.dataset.search || '';
                    const categoryId = element.dataset.categoryId || '';
                    
                    const matchesSearch = !searchTerm || searchData.includes(searchTerm);
                    const matchesCategory = !selectedCategory || categoryId === selectedCategory;
                    
                    const isVisible = matchesSearch && matchesCategory;
                    element.style.display = isVisible ? '' : 'none';
                    
                    if (isVisible) visibleCount++;
                }

                // Filter table rows
                rows.forEach(filterItem);
                
                // Filter grid cards
                cards.forEach(filterItem);

                // Update search results
                updateSearchResults(searchTerm, selectedCategory, visibleCount);
            }

            function updateSearchResults(searchTerm, selectedCategory, visibleCount) {
                if (searchTerm || selectedCategory) {
                    searchResults.classList.remove('hidden');
                    let message = `Hiển thị ${visibleCount} sản phẩm`;
                    
                    if (searchTerm) {
                        message += ` cho "${searchTerm}"`;
                    }
                    
                    if (selectedCategory) {
                        const categoryName = categoryFilter.options[categoryFilter.selectedIndex].text;
                        message += ` trong danh mục "${categoryName}"`;
                    }
                    
                    searchResultsText.textContent = message;
                } else {
                    searchResults.classList.add('hidden');
                }
            }

            // Event listeners
            searchInput.addEventListener('input', performSearch);
            categoryFilter.addEventListener('change', performSearch);

            clearFilters.addEventListener('click', function() {
                searchInput.value = '';
                categoryFilter.value = '';
                performSearch();
            });

            // View toggle
            toggleView.addEventListener('click', function() {
                if (currentView === 'table') {
                    tableView.classList.add('hidden');
                    gridView.classList.remove('hidden');
                    toggleView.innerHTML = '<i class="fas fa-table mr-1"></i>Dạng bảng';
                    toggleView.dataset.view = 'grid';
                    currentView = 'grid';
                } else {
                    tableView.classList.remove('hidden');
                    gridView.classList.add('hidden');
                    toggleView.innerHTML = '<i class="fas fa-th-large mr-1"></i>Dạng lưới';
                    toggleView.dataset.view = 'table';
                    currentView = 'table';
                }
            });

            // Delete confirmation
            document.querySelectorAll('form[method="POST"]').forEach(form => {
                form.addEventListener('submit', function(e) {
                    if (this.querySelector('button[type="submit"]').classList.contains('btn-danger')) {
                        if (!confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')) {
                            e.preventDefault();
                        }
                    }
                });
            });
        });
    </script>
    @endpush
</x-app-layout>
