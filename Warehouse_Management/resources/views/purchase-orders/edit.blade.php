<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Chỉnh sửa hóa đơn nhập kho #{{ $purchaseOrder->invoice_number }}
            </h2>
            <a href="{{ route('purchase-orders.show', $purchaseOrder) }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Quay lại
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('purchase-orders.update', $purchaseOrder) }}" method="POST" id="purchaseOrderForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Thông tin cơ bản -->
                            <div class="space-y-6">
                                <div>
                                    <label for="warehouse_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Kho hàng <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="text" 
                                               id="warehouse_search" 
                                               placeholder="Tìm kiếm kho hàng..."
                                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white mb-2">
                                        <select name="warehouse_id" 
                                                id="warehouse_id" 
                                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white @error('warehouse_id') border-red-500 @enderror" 
                                                required>
                                            <option value="">Chọn kho hàng</option>
                                            @foreach($warehouses as $warehouse)
                                                <option value="{{ $warehouse->id }}" 
                                                    {{ (old('warehouse_id', $purchaseOrder->warehouse_id) == $warehouse->id) ? 'selected' : '' }}>
                                                    {{ $warehouse->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('warehouse_id')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="supplier_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Tên nhà cung cấp <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           name="supplier_name" 
                                           id="supplier_name" 
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white @error('supplier_name') border-red-500 @enderror" 
                                           value="{{ old('supplier_name', $purchaseOrder->supplier_name) }}" 
                                           required>
                                    @error('supplier_name')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="supplier_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Số điện thoại
                                    </label>
                                    <input type="text" 
                                           name="supplier_phone" 
                                           id="supplier_phone" 
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white @error('supplier_phone') border-red-500 @enderror" 
                                           value="{{ old('supplier_phone', $purchaseOrder->supplier_phone) }}">
                                    @error('supplier_phone')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="space-y-6">                                <div>
                                    <label for="invoice_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Số hóa đơn
                                    </label>
                                    <div class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-gray-50 dark:bg-gray-600 text-gray-700 dark:text-gray-300">
                                        {{ $purchaseOrder->invoice_number }}
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Số hóa đơn không thể thay đổi</p>
                                </div>

                                <div>
                                    <label for="supplier_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Địa chỉ nhà cung cấp
                                    </label>
                                    <textarea name="supplier_address" 
                                              id="supplier_address" 
                                              rows="3"
                                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white @error('supplier_address') border-red-500 @enderror">{{ old('supplier_address', $purchaseOrder->supplier_address) }}</textarea>
                                    @error('supplier_address')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Ghi chú
                                    </label>
                                    <textarea name="notes" 
                                              id="notes" 
                                              rows="2"
                                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white @error('notes') border-red-500 @enderror">{{ old('notes', $purchaseOrder->notes) }}</textarea>
                                    @error('notes')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-gray-200 dark:border-gray-600 mt-8 pt-8">
                            <!-- Chi tiết sản phẩm -->
                            <div class="mb-6">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Chi tiết sản phẩm</h3>
                                    <button type="button" 
                                            class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150" 
                                            id="addItemBtn">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                        </svg>
                                        Thêm sản phẩm
                                    </button>
                                </div>

                                <div class="overflow-x-auto">
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
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-1/12">
                                                    Thao tác
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="itemsBody" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                            <!-- Items will be populated by JavaScript -->
                                        </tbody>
                                        <tfoot class="bg-gray-50 dark:bg-gray-700">
                                            <tr>
                                                <th colspan="3" class="px-6 py-3 text-right text-sm font-semibold text-gray-900 dark:text-gray-100">
                                                    Tổng cộng:
                                                </th>
                                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-gray-100" id="totalAmount">
                                                    0 VNĐ
                                                </th>
                                                <th class="px-6 py-3"></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" 
                                        class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                                    </svg>
                                    Cập nhật hóa đơn
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    let itemIndex = 0;
    const products = @json($products);
    const existingItems = @json($purchaseOrder->items);

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize warehouse search
        initializeWarehouseSearch();
        
        // Populate existing items
        existingItems.forEach(function(item) {
            addItem(item);
        });
        
        // If no existing items, add one empty item
        if (existingItems.length === 0) {
            addItem();
        }
        
        document.getElementById('addItemBtn').addEventListener('click', function() {
            addItem();
        });
    });

    function initializeWarehouseSearch() {
        const searchInput = document.getElementById('warehouse_search');
        const selectElement = document.getElementById('warehouse_id');
        const options = Array.from(selectElement.options);
        
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            // Clear current options except the first one
            selectElement.innerHTML = '<option value="">Chọn kho hàng</option>';
            
            // Filter and add matching options
            options.slice(1).forEach(option => {
                if (option.textContent.toLowerCase().includes(searchTerm)) {
                    selectElement.appendChild(option.cloneNode(true));
                }
            });
        });
        
        // Update search input when selection changes
        selectElement.addEventListener('change', function() {
            if (this.value) {
                const selectedOption = this.options[this.selectedIndex];
                searchInput.value = selectedOption.textContent;
            } else {
                searchInput.value = '';
            }
        });
        
        // Set initial search value if there's a selected warehouse
        if (selectElement.value) {
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            searchInput.value = selectedOption.textContent;
        }
    }

    function createProductSearchSelect(index, selectedProductId = null) {
        const searchId = `product_search_${index}`;
        const selectId = `product_select_${index}`;
        
        return `
            <div class="space-y-2">
                <input type="text" 
                       id="${searchId}" 
                       placeholder="Tìm kiếm sản phẩm..."
                       class="w-full px-3 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                <select name="items[${index}][product_id]" 
                        id="${selectId}"
                        class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white product-select" 
                        required>
                    <option value="">Chọn sản phẩm</option>
                    ${products.map(product => 
                        `<option value="${product.id}" ${selectedProductId == product.id ? 'selected' : ''}>${product.name}</option>`
                    ).join('')}
                </select>
            </div>
        `;
    }

    function initializeProductSearch(index, selectedProductId = null) {
        const searchInput = document.getElementById(`product_search_${index}`);
        const selectElement = document.getElementById(`product_select_${index}`);
        const options = Array.from(selectElement.options);
        
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            // Clear current options except the first one
            selectElement.innerHTML = '<option value="">Chọn sản phẩm</option>';
            
            // Filter and add matching options
            options.slice(1).forEach(option => {
                if (option.textContent.toLowerCase().includes(searchTerm)) {
                    selectElement.appendChild(option.cloneNode(true));
                }
            });
        });
        
        // Update search input when selection changes
        selectElement.addEventListener('change', function() {
            if (this.value) {
                const selectedOption = this.options[this.selectedIndex];
                searchInput.value = selectedOption.textContent;
            } else {
                searchInput.value = '';
            }
        });
        
        // Set initial search value if there's a selected product
        if (selectedProductId) {
            const product = products.find(p => p.id == selectedProductId);
            if (product) {
                searchInput.value = product.name;
            }
        }
    }

    function addItem(existingItem = null) {
        const itemHtml = `
            <tr class="item-row bg-white dark:bg-gray-800" data-index="${itemIndex}">
                <td class="px-6 py-4 whitespace-nowrap">
                    ${createProductSearchSelect(itemIndex, existingItem ? existingItem.product_id : null)}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <input type="number" 
                           name="items[${itemIndex}][quantity]" 
                           class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white quantity-input" 
                           min="1" 
                           value="${existingItem ? existingItem.quantity : ''}" 
                           required>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <input type="number" 
                           name="items[${itemIndex}][unit_price]" 
                           class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white price-input" 
                           min="0" 
                           step="0.01" 
                           value="${existingItem ? existingItem.unit_price : ''}" 
                           required>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100 total-price">
                    0 VNĐ
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <button type="button" 
                            class="inline-flex items-center px-3 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150 remove-item">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </td>
            </tr>
        `;
        
        document.getElementById('itemsBody').insertAdjacentHTML('beforeend', itemHtml);
        
        // Initialize product search for this row
        initializeProductSearch(itemIndex, existingItem ? existingItem.product_id : null);
        
        // Bind events to new row
        const newRow = document.querySelector(`tr[data-index="${itemIndex}"]`);
        bindItemEvents(newRow);
        
        // Update row total if existing item
        if (existingItem) {
            updateRowTotal(newRow);
        }
        
        itemIndex++;
        updateRemoveButtons();
        updateGrandTotal();
    }

    function bindItemEvents(row) {
        const quantityInput = row.querySelector('.quantity-input');
        const priceInput = row.querySelector('.price-input');
        const removeButton = row.querySelector('.remove-item');
        
        quantityInput.addEventListener('input', function() {
            updateRowTotal(row);
            updateGrandTotal();
        });
        
        priceInput.addEventListener('input', function() {
            updateRowTotal(row);
            updateGrandTotal();
        });
        
        removeButton.addEventListener('click', function() {
            row.remove();
            updateRemoveButtons();
            updateGrandTotal();
        });
    }

    function updateRowTotal(row) {
        const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
        const unitPrice = parseFloat(row.querySelector('.price-input').value) || 0;
        const total = quantity * unitPrice;
        
        row.querySelector('.total-price').textContent = formatCurrency(total);
    }

    function updateGrandTotal() {
        let grandTotal = 0;
        
        document.querySelectorAll('.item-row').forEach(function(row) {
            const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
            const unitPrice = parseFloat(row.querySelector('.price-input').value) || 0;
            grandTotal += quantity * unitPrice;
        });
        
        document.getElementById('totalAmount').textContent = formatCurrency(grandTotal);
    }

    function updateRemoveButtons() {
        const rows = document.querySelectorAll('.item-row');
        const removeButtons = document.querySelectorAll('.remove-item');
        
        if (rows.length === 1) {
            removeButtons.forEach(button => button.disabled = true);
        } else {
            removeButtons.forEach(button => button.disabled = false);
        }
    }

    function formatCurrency(amount) {
        return new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND'
        }).format(amount);
    }
    </script>
    @endpush
</x-app-layout>
