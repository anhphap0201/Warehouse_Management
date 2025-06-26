<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Tạo hóa đơn nhập kho') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('purchase-orders.index') }}" 
                   class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Quay lại
                </a>
            </div>
        </div>
    </x-slot>    <div class="py-12">
        <div class="w-9/12 mx-auto sm:px-6 lg:px-8">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title mb-6">Tạo hóa đơn nhập kho</h3>

                    @if(session('success'))
                        <div class="alert alert-success mb-6">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-error mb-6">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-error mb-6">
                            <div class="font-bold">Có lỗi xảy ra:</div>
                            <ul class="list-disc list-inside mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('purchase-orders.store') }}" method="POST" id="purchaseOrderForm">
                        @csrf
                        
                        <div class="form-grid-2">
                            <!-- Thông tin cơ bản -->
                            <div class="space-y-6">
                                <div>
                                    <label for="warehouse_id" class="form-label">
                                        Kho hàng <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="text" 
                                               id="warehouse_search" 
                                               placeholder="Tìm kiếm kho hàng..."
                                               class="form-input mb-2"
                                               autocomplete="off">
                                        <input type="hidden" name="warehouse_id" id="warehouse_id" value="{{ old('warehouse_id') }}">
                                        <div id="warehouse_dropdown" class="absolute z-[9999] w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-2xl mt-1 max-h-60 overflow-y-auto hidden" style="z-index: 9999 !important;">
                                            <div id="warehouse_loading" class="px-4 py-2 text-gray-500 dark:text-gray-400 text-sm hidden">
                                                <div class="flex items-center">
                                                    <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-500 mr-2"></div>
                                                    Đang tìm kiếm...
                                                </div>
                                            </div>
                                            <div id="warehouse_results"></div>
                                        </div>
                                    </div>
                                    @error('warehouse_id')
                                        <p class="form-error">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                            </div>
                            <div class="space-y-6">
                                <div>
                                    <label for="supplier_name" class="form-label">
                                        Tên nhà cung cấp <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="text" 
                                               id="supplier_search" 
                                               placeholder="Tìm kiếm nhà cung cấp..."
                                               class="form-input @error('supplier_name') is-invalid @enderror"
                                               autocomplete="off">
                                        <input type="hidden" name="supplier_name" id="supplier_name" value="{{ old('supplier_name') }}">
                                        <input type="hidden" name="supplier_id" id="supplier_id" value="{{ old('supplier_id') }}">
                                        <div id="supplier_dropdown" class="absolute z-[9999] w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-2xl mt-1 max-h-60 overflow-y-auto hidden" style="z-index: 9999 !important;">
                                            <div id="supplier_loading" class="px-4 py-2 text-gray-500 dark:text-gray-400 text-sm hidden">
                                                <div class="flex items-center">
                                                    <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-500 mr-2"></div>
                                                    Đang tìm kiếm...
                                                </div>
                                            </div>
                                            <div id="supplier_results"></div>
                                        </div>
                                    </div>
                                    @error('supplier_name')
                                        <p class="form-error">{{ $message }}</p>
                                    @enderror
                                </div>
                                    <div>
                                        <label for="supplier_phone" class="form-label">Số điện thoại</label>
                                        <input type="text" 
                                            name="supplier_phone" 
                                            id="supplier_phone" 
                                            class="form-input @error('supplier_phone') is-invalid @enderror" 
                                            value="{{ old('supplier_phone') }}">
                                        @error('supplier_phone')
                                            <p class="form-error">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div id="supplier_address_field" style="display: none;">
                                    <label for="supplier_address" class="form-label">Địa chỉ nhà cung cấp</label>
                                    <textarea name="supplier_address" 
                                              id="supplier_address" 
                                              class="form-textarea @error('supplier_address') is-invalid @enderror" 
                                              rows="3"
                                              placeholder="Nhập địa chỉ nhà cung cấp...">{{ old('supplier_address') }}</textarea>
                                    @error('supplier_address')
                                        <p class="form-error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="notes" class="form-label">Ghi chú</label>
                                    <textarea name="notes" 
                                              id="notes" 
                                              class="form-textarea @error('notes') is-invalid @enderror" 
                                              rows="2">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <p class="form-error">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-gray-200 dark:border-gray-700 my-6"></div>

                        <!-- Chi tiết sản phẩm -->
                        <div class="mb-6">
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Chi tiết sản phẩm</h4>
                                <button type="button" 
                                        class="btn btn-success btn-sm" 
                                        id="addItemBtn">
                                    <i class="fas fa-plus mr-2"></i>
                                    Thêm sản phẩm
                                </button>
                            </div>                            <div class="overflow-x-auto" style="overflow: visible;">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 border border-gray-200 dark:border-gray-700 rounded-lg" id="itemsTable">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-2/5">
                                                Sản phẩm
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-1/6">
                                                Số lượng
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-1/5">
                                                Đơn giá
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-1/5">
                                                Thành tiền
                                            </th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-1/12">
                                                Thao tác
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700" id="itemsBody">
                                        <!-- Items will be added here by JavaScript -->
                                    </tbody>
                                    <tfoot class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-900 dark:text-gray-100">
                                                Tổng cộng:
                                            </th>
                                            <th class="px-6 py-3 text-left text-sm font-bold text-gray-900 dark:text-gray-100" id="totalAmount">
                                                0 VNĐ
                                            </th>
                                            <th class="px-6 py-3"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <!-- Global Product Dropdown Container -->
                            <div id="globalProductDropdown" class="fixed z-[10000] bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-2xl max-h-60 overflow-y-auto hidden" style="z-index: 10000 !important; min-width: 300px;">
                                <div id="globalProductLoading" class="px-4 py-2 text-gray-500 dark:text-gray-400 text-sm hidden">
                                    <div class="flex items-center">
                                        <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-500 mr-2"></div>
                                        Đang tìm kiếm...
                                    </div>
                                </div>
                                <div id="globalProductResults"></div>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" 
                                    class="btn btn-primary">
                                <i class="fas fa-save mr-2"></i>
                                Lưu hóa đơn
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
let itemIndex = 0;
let searchTimeout;

// Lấy token CSRF từ meta tag
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Lấy giá trị cũ nếu validation thất bại
const oldItems = @json(old('items', []));

document.addEventListener('DOMContentLoaded', function() {
    // Khởi tạo chức năng tìm kiếm thời gian thực
    initializeWarehouseSearch();
    initializeSupplierSearch();
    
    // Khôi phục giá trị cũ cho trường tìm kiếm nếu validation thất bại
    restoreSearchFieldValues();
    
    // Khôi phục giá trị cũ nếu validation thất bại
    if (oldItems && oldItems.length > 0) {
        oldItems.forEach(function(item, index) {
            addItem(item);
        });
    } else {
        // Thêm item đầu tiên theo mặc định
        addItem();
    }
    
    document.getElementById('addItemBtn').addEventListener('click', function() {
        addItem();
    });
    
    // Xử lý click toàn cục để ẩn dropdown sản phẩm
    document.addEventListener('click', function(e) {
        const globalDropdown = document.getElementById('globalProductDropdown');
        const isClickInsideDropdown = globalDropdown && globalDropdown.contains(e.target);
        const isClickOnProductInput = e.target && e.target.id && e.target.id.startsWith('product_search_');
        
        if (!isClickInsideDropdown && !isClickOnProductInput) {
            globalDropdown && globalDropdown.classList.add('hidden');
        }
        
        // Đóng các dropdown khác khi click bên ngoài
        if (!e.target.closest('.relative')) {
            document.querySelectorAll('[id$="_dropdown"]').forEach(dropdown => {
                dropdown.classList.add('hidden');
            });
        }
    });
    
    // Xử lý scroll và resize của window để điều chỉnh vị trí dropdown
    let repositionTimeout;
    function handleRepositioning() {
        clearTimeout(repositionTimeout);
        repositionTimeout = setTimeout(() => {
            const globalDropdown = document.getElementById('globalProductDropdown');
            if (!globalDropdown.classList.contains('hidden')) {
                globalDropdown.classList.add('hidden');
            }
        }, 100);
    }
    
    window.addEventListener('scroll', handleRepositioning);
    window.addEventListener('resize', handleRepositioning);
});

function restoreSearchFieldValues() {
    // Khôi phục trường tìm kiếm kho hàng
    const warehouseId = document.getElementById('warehouse_id').value;
    const warehouseSearch = document.getElementById('warehouse_search');
    
    if (warehouseId && warehouseSearch) {
        // Gọi API để lấy tên kho hàng
        fetch(`/api/warehouses/${warehouseId}`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            credentials: 'same-origin'
        }).then(response => {
            if (response.ok) {
                return response.json();
            }
        }).then(warehouse => {
            if (warehouse) {
                warehouseSearch.value = warehouse.name;
            }
        }).catch(error => {
            console.error('Error fetching warehouse:', error);
        });
    }
    
    // Khôi phục trường tìm kiếm nhà cung cấp
    const supplierName = document.getElementById('supplier_name').value;
    const supplierSearch = document.getElementById('supplier_search');
    
    if (supplierName && supplierSearch) {
        supplierSearch.value = supplierName;
    }
}

function initializeWarehouseSearch() {
    const searchInput = document.getElementById('warehouse_search');
    const hiddenInput = document.getElementById('warehouse_id');
    const dropdown = document.getElementById('warehouse_dropdown');
    const loading = document.getElementById('warehouse_loading');
    const results = document.getElementById('warehouse_results');    searchInput.addEventListener('input', function() {
        const query = this.value.trim();
        
        if (query.length < 1) {
            dropdown.classList.add('hidden');
            hiddenInput.value = '';
            return;
        }
        
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            searchWarehouses(query);
        }, 300);
    });
      async function searchWarehouses(query) {
        loading.classList.remove('hidden');
        results.innerHTML = '';
        dropdown.classList.remove('hidden');
        
        try {
            const response = await fetch(`/api/warehouses/search?q=${encodeURIComponent(query)}`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                credentials: 'same-origin'
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
            loading.classList.add('hidden');
            
            if (data.length === 0) {
                results.innerHTML = '<div class="px-4 py-2 text-gray-500 dark:text-gray-400 text-sm">Không tìm thấy kho hàng nào</div>';
            } else {
                results.innerHTML = data.map(warehouse => `
                    <div class="px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer border-b border-gray-200 dark:border-gray-600 last:border-b-0" 
                         onclick="selectWarehouse(${warehouse.id}, '${warehouse.name}')">
                        <div class="font-medium text-gray-900 dark:text-gray-100">${warehouse.name}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">${warehouse.address || 'Không có địa chỉ'}</div>
                    </div>
                `).join('');
            }
        } catch (error) {
            console.error('Warehouse search error:', error);
            loading.classList.add('hidden');
            results.innerHTML = '<div class="px-4 py-2 text-red-500 text-sm">Lỗi khi tìm kiếm</div>';
        }
    }
    
    window.selectWarehouse = function(id, name) {
        hiddenInput.value = id;
        searchInput.value = name;
        dropdown.classList.add('hidden');
    };
}

function initializeSupplierSearch() {
    const searchInput = document.getElementById('supplier_search');
    const hiddenInput = document.getElementById('supplier_name');
    const dropdown = document.getElementById('supplier_dropdown');
    const loading = document.getElementById('supplier_loading');
    const results = document.getElementById('supplier_results');    searchInput.addEventListener('input', function() {        const query = this.value.trim();
        const supplierAddressField = document.getElementById('supplier_address_field');
        
        hiddenInput.value = query; // Luôn cập nhật giá trị input ẩn
        // Đã loại bỏ việc tự động hiển thị trường địa chỉ nhà cung cấp
        
        if (query.length < 1) {
            dropdown.classList.add('hidden');
            return;
        }
        
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            searchSuppliers(query);
        }, 300);
    });
      async function searchSuppliers(query) {
        loading.classList.remove('hidden');
        results.innerHTML = '';
        dropdown.classList.remove('hidden');
        
        try {
            const response = await fetch(`/api/suppliers/search?q=${encodeURIComponent(query)}`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                credentials: 'same-origin'
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
            loading.classList.add('hidden');
            
            if (data.length === 0) {
                results.innerHTML = '<div class="px-4 py-2 text-gray-500 dark:text-gray-400 text-sm">Không tìm thấy nhà cung cấp nào</div>';            } else {                results.innerHTML = data.map(supplier => `
                    <div class="px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer border-b border-gray-200 dark:border-gray-600 last:border-b-0" 
                         onclick="selectSupplier(${supplier.id}, '${supplier.name}', '${supplier.phone || ''}', '${supplier.address || ''}')">
                        <div class="font-medium text-gray-900 dark:text-gray-100">${supplier.name}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">${supplier.phone || 'Không có SĐT'} • ${supplier.email || 'Không có email'}</div>
                    </div>
                `).join('');
            }
        } catch (error) {
            console.error('Supplier search error:', error);
            loading.classList.add('hidden');
            results.innerHTML = '<div class="px-4 py-2 text-red-500 text-sm">Lỗi khi tìm kiếm</div>';
        }
    }    window.selectSupplier = function(id, name, phone, address) {
        const supplierNameInput = document.getElementById('supplier_name');
        const supplierIdInput = document.getElementById('supplier_id');
        const supplierPhoneInput = document.getElementById('supplier_phone');
        const supplierAddressInput = document.getElementById('supplier_address');
        const supplierAddressField = document.getElementById('supplier_address_field');
        const searchInput = document.getElementById('supplier_search');
        
        supplierIdInput.value = id;
        supplierNameInput.value = name;
        searchInput.value = name;        
        // Tự động điền các trường khác nếu chúng tồn tại và trống
        if (supplierPhoneInput && !supplierPhoneInput.value) {
            supplierPhoneInput.value = phone || '';
        }
        if (supplierAddressInput && !supplierAddressInput.value) {
            supplierAddressInput.value = address || '';
        }
        
        // Luôn ẩn trường địa chỉ khi nhà cung cấp được chọn từ dropdown
        if (supplierAddressField) {
            supplierAddressField.style.display = 'none';
        }
        
        dropdown.classList.add('hidden');
    };
}

function createProductSearchSelect(index) {
    return `
        <div class="relative">
            <input type="text" 
                   id="product_search_${index}" 
                   placeholder="Tìm kiếm sản phẩm..."
                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md leading-5 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                   autocomplete="off">
            <input type="hidden" name="items[${index}][product_id]" id="product_id_${index}" required>
        </div>
    `;
}

function initializeProductSearch(index) {
    const searchInput = document.getElementById(`product_search_${index}`);
    const hiddenInput = document.getElementById(`product_id_${index}`);
    const globalDropdown = document.getElementById('globalProductDropdown');
    const globalLoading = document.getElementById('globalProductLoading');
    const globalResults = document.getElementById('globalProductResults');
    
    // Kiểm tra xem các element có tồn tại không
    if (!searchInput || !hiddenInput || !globalDropdown) {
        console.error('Product search elements not found for index:', index);
        return;
    }
    
    let currentSearchIndex = null;
    
    searchInput.addEventListener('input', function() {
        const query = this.value.trim();
        currentSearchIndex = index;
        
        console.log('Product search input:', query, 'for index:', index);
        
        if (query.length < 1) {
            globalDropdown.classList.add('hidden');
            hiddenInput.value = '';
            return;
        }
        
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            searchProducts(query, index);
        }, 300);
    });
    
    searchInput.addEventListener('focus', function() {
        currentSearchIndex = index;
        const query = this.value.trim();
        console.log('Product search focus:', query, 'for index:', index);
        if (query.length >= 1) {
            searchProducts(query, index);
        }
    });
      searchInput.addEventListener('blur', function() {
        // Ẩn dropdown sau khoảng thời gian ngắn để cho phép click
        setTimeout(() => {
            if (currentSearchIndex === index) {
                globalDropdown.classList.add('hidden');
            }
        }, 200);
    });
      async function searchProducts(query, index) {
        console.log('Searching products with query:', query, 'for index:', index);
        
        // Định vị dropdown theo vị trí input
        const rect = searchInput.getBoundingClientRect();
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const scrollLeft = window.pageXOffset || document.documentElement.scrollLeft;
        
        globalDropdown.style.top = (rect.bottom + scrollTop + 2) + 'px';
        globalDropdown.style.left = (rect.left + scrollLeft) + 'px';
        globalDropdown.style.width = rect.width + 'px';
        
        globalLoading.classList.remove('hidden');
        globalResults.innerHTML = '';
        globalDropdown.classList.remove('hidden');
        
        try {
            const url = `/api/products/search?q=${encodeURIComponent(query)}`;
            console.log('Fetching URL:', url);
            
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                credentials: 'same-origin'
            });
            
            console.log('Response status:', response.status);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            console.log('Products found:', data);
            
            globalLoading.classList.add('hidden');
            
            if (data.length === 0) {
                globalResults.innerHTML = '<div class="px-4 py-2 text-gray-500 dark:text-gray-400 text-sm">Không tìm thấy sản phẩm nào</div>';
            } else {
                globalResults.innerHTML = data.map(product => `
                    <div class="px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer border-b border-gray-200 dark:border-gray-600 last:border-b-0" 
                         onclick="selectProduct(${product.id}, '${product.name}', ${index})"
                         onmousedown="event.preventDefault()">
                        <div class="font-medium text-gray-900 dark:text-gray-100">${product.name}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Mã: ${product.code || product.sku || 'N/A'}</div>
                    </div>
                `).join('');
            }
        } catch (error) {
            console.error('Product search error:', error);
            globalLoading.classList.add('hidden');
            globalResults.innerHTML = '<div class="px-4 py-2 text-red-500 text-sm">Lỗi khi tìm kiếm: ' + error.message + '</div>';
        }
    }
    
    window.selectProduct = function(id, name, index) {
        document.getElementById(`product_id_${index}`).value = id;
        document.getElementById(`product_search_${index}`).value = name;
        globalDropdown.classList.add('hidden');
    };
}

function addItem(oldItem = null) {
    const itemHtml = `
        <tr class="item-row hover:bg-gray-50 dark:hover:bg-gray-700" data-index="${itemIndex}">
            <td class="px-6 py-4">
                ${createProductSearchSelect(itemIndex)}
            </td>
            <td class="px-6 py-4">
                <input type="number" 
                       name="items[${itemIndex}][quantity]" 
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md leading-5 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 quantity-input" 
                       min="1" 
                       value="${oldItem ? oldItem.quantity || '' : ''}"
                       required>
            </td>
            <td class="px-6 py-4">
                <input type="number" 
                       name="items[${itemIndex}][unit_price]" 
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md leading-5 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 price-input" 
                       min="0" 
                       step="0.01" 
                       value="${oldItem ? oldItem.unit_price || '' : ''}"
                       required>
            </td>
            <td class="px-6 py-4 total-price text-sm font-medium text-gray-900 dark:text-gray-100">0 VNĐ</td>
            <td class="px-6 py-4 text-center">
                <button type="button" 
                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 remove-item ${itemIndex === 0 ? 'opacity-50 cursor-not-allowed' : ''}" 
                        ${itemIndex === 0 ? 'disabled' : ''}>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>                </button>
            </td>
        </tr>
    `;
    
    document.getElementById('itemsBody').insertAdjacentHTML('beforeend', itemHtml);
    
    // Khởi tạo tìm kiếm sản phẩm cho hàng này NGAY SAU KHI HTML được thêm
    console.log('Initializing product search for index:', itemIndex);
    setTimeout(() => {
        initializeProductSearch(itemIndex);
    }, 100);
    
    // Nếu dữ liệu oldItem tồn tại, điền vào trường tìm kiếm sản phẩm
    if (oldItem && oldItem.product_id) {
        const productSearchInput = document.getElementById(`product_search_${itemIndex}`);
        const productIdInput = document.getElementById(`product_id_${itemIndex}`);
        
        if (productSearchInput && productIdInput) {
            // Tắt input trong khi tải để ngăn người dùng nhập
            productSearchInput.disabled = true;
            productSearchInput.value = 'Đang tải thông tin sản phẩm...';
            productSearchInput.classList.add('bg-gray-100', 'text-gray-500');
            
            // Thiết lập product_id ẩn
            productIdInput.value = oldItem.product_id;
            
            // Thử tìm và thiết lập tên sản phẩm từ tìm kiếm
            fetch(`/api/products/${oldItem.product_id}`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                credentials: 'same-origin'
            }).then(response => {
                if (response.ok) {
                    return response.json();
                }
                throw new Error(`HTTP ${response.status}`);
            }).then(product => {
                if (product) {
                    productSearchInput.value = product.name;                    productSearchInput.disabled = false;
                    productSearchInput.classList.remove('bg-gray-100', 'text-gray-500');
                    
                    // Lưu tên sản phẩm gốc để xác thực
                    productSearchInput.dataset.originalProductName = product.name;
                    productSearchInput.dataset.originalProductId = oldItem.product_id;
                }
            }).catch(error => {
                console.error('Error fetching product:', error);                productSearchInput.value = 'Sản phẩm không tồn tại (ID: ' + oldItem.product_id + ')';
                productSearchInput.classList.add('border-red-500', 'bg-red-50');
                productSearchInput.disabled = false;
                
                // Xóa product_id vì sản phẩm không tồn tại
                productIdInput.value = '';
            });
        }    }
    
    // Khởi tạo tìm kiếm sản phẩm cho hàng này
    initializeProductSearch(itemIndex);
    
    // Gán sự kiện cho hàng mới
    const newRow = document.querySelector(`tr[data-index="${itemIndex}"]`);
    bindItemEvents(newRow);
    
    // Cập nhật tổng hàng nếu có dữ liệu hiện có
    if (oldItem && oldItem.quantity && oldItem.unit_price) {
        updateRowTotal(newRow);
    }
    
    itemIndex++;
    updateRemoveButtons();
}

function bindItemEvents(row) {
    const quantityInput = row.querySelector('.quantity-input');
    const priceInput = row.querySelector('.price-input');
    const removeButton = row.querySelector('.remove-item');
    
    [quantityInput, priceInput].forEach(input => {
        input.addEventListener('input', function() {
            updateRowTotal(row);
            updateGrandTotal();
        });
    });
    
    removeButton.addEventListener('click', function() {
        if (!removeButton.disabled) {
            row.remove();
            updateRemoveButtons();
            updateGrandTotal();
        }
    });
}

function updateRowTotal(row) {    const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
    const unitPrice = parseFloat(row.querySelector('.price-input').value) || 0;
    
    // Xác thực số lượng và đơn giá để ngăn tràn cơ sở dữ liệu
    const maxQuantity = 999999999; // Tối đa 9 chữ số cho số lượng
    const maxUnitPrice = 9999999999999.99; // Tối đa 13 chữ số + 2 thập phân cho đơn giá
    
    if (quantity > maxQuantity) {
        row.querySelector('.quantity-input').value = maxQuantity;
        alert('Số lượng không được vượt quá ' + maxQuantity.toLocaleString());
        return;
    }
    
    if (unitPrice > maxUnitPrice) {
        row.querySelector('.price-input').value = maxUnitPrice;
        alert('Đơn giá không được vượt quá ' + maxUnitPrice.toLocaleString() + ' VNĐ');
        return;
    }
      const total = quantity * unitPrice;
    
    // Kiểm tra xem tổng có vượt quá giới hạn cơ sở dữ liệu không
    const maxTotal = 9999999999999.99; // Giới hạn decimal(15,2)
    if (total > maxTotal) {
        alert('Tổng tiền vượt quá giới hạn cho phép. Vui lòng giảm số lượng hoặc đơn giá.');
        return;
    }
    
    row.querySelector('.total-price').textContent = formatCurrency(total);
}

function updateGrandTotal() {
    let grandTotal = 0;
    
    document.querySelectorAll('.item-row').forEach(row => {
        const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
        const unitPrice = parseFloat(row.querySelector('.price-input').value) || 0;
        grandTotal += quantity * unitPrice;    });
    
    // Kiểm tra xem tổng lớn có vượt quá giới hạn cơ sở dữ liệu không
    const maxTotal = 9999999999999.99; // Giới hạn decimal(15,2)
    if (grandTotal > maxTotal) {
        document.getElementById('totalAmount').textContent = 'Vượt quá giới hạn!';
        document.getElementById('totalAmount').style.color = 'red';
        return;
    } else {
        document.getElementById('totalAmount').style.color = '';
    }
    
    document.getElementById('totalAmount').textContent = formatCurrency(grandTotal);
}

function updateRemoveButtons() {
    const rows = document.querySelectorAll('.item-row');
    rows.forEach(row => {
        const removeButton = row.querySelector('.remove-item');
        if (rows.length === 1) {
            removeButton.disabled = true;
            removeButton.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            removeButton.disabled = false;
            removeButton.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    });
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(amount);
}

</script>
