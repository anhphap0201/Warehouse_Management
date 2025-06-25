<x-app-layout>
    <x-slot name="header">
        <div class="page-header-standard">
            <div class="page-header-content">
                <div class="page-title-section">
                    <h1 class="page-title-main text-gray-900">
                        <div class="page-title-icon simple-bg">
                            <i class="fas fa-undo text-orange-600 text-lg"></i>
                        </div>
                        {{ __('Tạo Đơn Trả Hàng') }}
                    </h1>
                    <p class="page-subtitle">Tạo đơn trả hàng mới - hàng sẽ không bị trừ khỏi kho ngay lập tức</p>
                </div>
                <div class="page-actions">
                    <a href="{{ route('return-orders.index') }}" class="btn-secondary-standard">
                        <i class="fas fa-arrow-left mr-2"></i>
                        {{ __('Quay lại') }}
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="page-container">
        <div class="page-content">
            <div class="max-w-4xl mx-auto">
                <form action="{{ route('return-orders.store') }}" method="POST" id="returnOrderForm">
                    @csrf
                      <!-- Thông tin cơ bản -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
                        <div class="p-6">
                            <div class="section-divider">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                                    <i class="fas fa-info-circle mr-2 text-blue-600"></i>
                                    Thông tin đơn trả hàng
                                </h3>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">                                <!-- Kho hàng -->
                                <div class="grid-divider-vertical">
                                    <label for="warehouse_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Kho hàng <span class="text-red-500">*</span>
                                    </label>
                                    <select name="warehouse_id" id="warehouse_id" required 
                                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-300">
                                        <option value="">Chọn kho hàng...</option>
                                        @foreach($warehouses as $warehouse)
                                            <option value="{{ $warehouse->id }}" {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                                {{ $warehouse->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('warehouse_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Nhà cung cấp -->
                                <div>
                                    <label for="supplier_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Nhà cung cấp
                                    </label>
                                    <select name="supplier_id" id="supplier_id" 
                                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-300">
                                        <option value="">Chọn nhà cung cấp (tùy chọn)...</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" 
                                                    data-name="{{ $supplier->name }}"
                                                    data-phone="{{ $supplier->phone }}"
                                                    data-address="{{ $supplier->address }}"
                                                    {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                                {{ $supplier->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>                                <!-- Tên nhà cung cấp -->
                                <div class="grid-divider-vertical">
                                    <label for="supplier_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Tên nhà cung cấp <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="supplier_name" id="supplier_name" required 
                                           value="{{ old('supplier_name') }}"
                                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-300">
                                    @error('supplier_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Số điện thoại -->
                                <div>
                                    <label for="supplier_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Số điện thoại
                                    </label>
                                    <input type="text" name="supplier_phone" id="supplier_phone" 
                                           value="{{ old('supplier_phone') }}"
                                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-300">
                                </div>                                <!-- Địa chỉ -->
                                <div class="md:col-span-2 grid-divider-horizontal">
                                    <label for="supplier_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Địa chỉ nhà cung cấp
                                    </label>
                                    <textarea name="supplier_address" id="supplier_address" rows="2"
                                              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-300">{{ old('supplier_address') }}</textarea>
                                </div>

                                <!-- Lý do trả hàng -->
                                <div class="md:col-span-2">
                                    <label for="return_reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Lý do trả hàng <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="return_reason" id="return_reason" rows="3" required
                                              placeholder="Mô tả lý do trả hàng..."
                                              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-300">{{ old('return_reason') }}</textarea>
                                    @error('return_reason')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>                    <!-- Danh sách sản phẩm -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
                        <div class="p-6">
                            <div class="section-divider">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                        <i class="fas fa-box mr-2 text-orange-600"></i>
                                        Sản phẩm trả hàng
                                    </h3>
                                    <button type="button" id="addProduct" class="btn-primary-standard">
                                        <i class="fas fa-plus mr-2"></i>
                                        Thêm sản phẩm
                                    </button>
                                </div>
                            </div>

                            <div id="productList">
                                <!-- Sản phẩm sẽ được thêm vào đây -->
                            </div>                            <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg info-divider">
                                <div class="flex justify-between items-center text-lg font-semibold">
                                    <span class="text-gray-700 dark:text-gray-300">Tổng tiền:</span>
                                    <span id="totalAmount" class="text-blue-600 dark:text-blue-400">0 VNĐ</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ghi chú -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                                <i class="fas fa-sticky-note mr-2 text-green-600"></i>
                                Ghi chú
                            </h3>
                            <textarea name="notes" rows="4" 
                                      placeholder="Ghi chú thêm về đơn trả hàng..."
                                      class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-300">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('return-orders.index') }}" class="btn-secondary-standard">
                            <i class="fas fa-times mr-2"></i>
                            Hủy
                        </a>
                        <button type="submit" class="btn-primary-standard">
                            <i class="fas fa-save mr-2"></i>
                            Tạo đơn trả hàng
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        let productIndex = 0;

        // Auto-fill supplier info when selected
        document.getElementById('supplier_id').addEventListener('change', function() {
            const option = this.options[this.selectedIndex];
            if (option.value) {
                document.getElementById('supplier_name').value = option.dataset.name || '';
                document.getElementById('supplier_phone').value = option.dataset.phone || '';
                document.getElementById('supplier_address').value = option.dataset.address || '';
            }
        });

        // Add product
        document.getElementById('addProduct').addEventListener('click', function() {
            addProductRow();
        });

        function addProductRow() {
            const productRow = `
                <div class="product-row bg-gray-50 dark:bg-gray-700 p-4 rounded-lg mb-4" data-index="${productIndex}">
                    <div class="grid grid-cols-1 md:grid-cols-6 gap-4 items-end">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Sản phẩm <span class="text-red-500">*</span>
                            </label>
                            <select name="items[${productIndex}][product_id]" required class="product-select w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:text-gray-300">
                                <option value="">Chọn sản phẩm...</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->sku }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Số lượng <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="items[${productIndex}][quantity]" min="1" required 
                                   class="quantity-input w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:text-gray-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Đơn giá <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="items[${productIndex}][unit_price]" min="0" step="0.01" required 
                                   class="price-input w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:text-gray-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Thành tiền
                            </label>
                            <input type="text" readonly class="total-price w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg dark:bg-gray-500 dark:text-gray-300">
                        </div>
                        <div>
                            <button type="button" class="remove-product w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors duration-200">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Lý do trả sản phẩm này
                        </label>
                        <input type="text" name="items[${productIndex}][return_reason]" 
                               placeholder="Lý do trả sản phẩm cụ thể..."
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:text-gray-300">
                    </div>
                </div>
            `;
            
            document.getElementById('productList').insertAdjacentHTML('beforeend', productRow);
            productIndex++;
            
            // Add event listeners for new row
            const newRow = document.querySelector(`[data-index="${productIndex - 1}"]`);
            addProductRowListeners(newRow);
        }

        function addProductRowListeners(row) {
            // Remove product
            row.querySelector('.remove-product').addEventListener('click', function() {
                row.remove();
                calculateTotal();
            });

            // Calculate line total
            const quantityInput = row.querySelector('.quantity-input');
            const priceInput = row.querySelector('.price-input');
            const totalInput = row.querySelector('.total-price');

            function calculateLineTotal() {
                const quantity = parseFloat(quantityInput.value) || 0;
                const price = parseFloat(priceInput.value) || 0;
                const total = quantity * price;
                totalInput.value = total.toLocaleString('vi-VN') + ' VNĐ';
                calculateTotal();
            }

            quantityInput.addEventListener('input', calculateLineTotal);
            priceInput.addEventListener('input', calculateLineTotal);
        }

        function calculateTotal() {
            let total = 0;
            document.querySelectorAll('.product-row').forEach(row => {
                const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
                const price = parseFloat(row.querySelector('.price-input').value) || 0;
                total += quantity * price;
            });
            
            document.getElementById('totalAmount').textContent = total.toLocaleString('vi-VN') + ' VNĐ';
        }

        // Add first product row
        addProductRow();
    });
    </script>
</x-app-layout>
