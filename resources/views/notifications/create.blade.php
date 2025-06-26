@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-4">
                <a href="{{ url()->previous() }}" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-800">Tạo yêu cầu mới</h1>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <form action="{{ route('notifications.store') }}" method="POST" id="notificationForm">
                @csrf
                
                <!-- Header Section -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4 text-white">
                    <h2 class="text-xl font-semibold">Thông tin yêu cầu</h2>
                    <p class="text-blue-100">Điền thông tin chi tiết cho yêu cầu của bạn</p>
                </div>

                <div class="p-6 space-y-6">
                    <!-- Basic Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="store_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Cửa hàng <span class="text-red-500">*</span>
                            </label>
                            <select name="store_id" id="store_id" required 
                                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Chọn cửa hàng</option>
                                @foreach($stores as $store)
                                    <option value="{{ $store->id }}" {{ old('store_id') == $store->id ? 'selected' : '' }}>
                                        {{ $store->name }} - {{ $store->location }}
                                    </option>
                                @endforeach
                            </select>
                            @error('store_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                                Loại yêu cầu <span class="text-red-500">*</span>
                            </label>
                            <select name="type" id="type" required onchange="updateFormLabels()"
                                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Chọn loại yêu cầu</option>
                                <option value="receive_request" {{ old('type') == 'receive_request' ? 'selected' : '' }}>
                                    Yêu cầu nhận hàng
                                </option>
                                <option value="return_request" {{ old('type') == 'return_request' ? 'selected' : '' }}>
                                    Yêu cầu trả hàng
                                </option>
                            </select>
                            @error('type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Title and Message -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Tiêu đề <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" id="title" required value="{{ old('title') }}"
                               class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Nhập tiêu đề yêu cầu...">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                            Nội dung chi tiết <span class="text-red-500">*</span>
                        </label>
                        <textarea name="message" id="message" rows="4" required
                                  class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Mô tả chi tiết về yêu cầu của bạn...">{{ old('message') }}</textarea>
                        @error('message')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Products Section -->
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">
                                <span id="productsLabel">Danh sách sản phẩm</span>
                                <span class="text-red-500">*</span>
                            </h3>
                            <button type="button" onclick="addProductRow()" 
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200">
                                <i class="fas fa-plus mr-2"></i>Thêm sản phẩm
                            </button>
                        </div>

                        <div id="productsContainer">
                            <!-- Product rows will be added here -->
                        </div>
                        
                        @error('products')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Priority -->
                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Độ ưu tiên</label>
                        <select name="priority" id="priority"
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="normal" {{ old('priority') == 'normal' ? 'selected' : '' }}>Bình thường</option>
                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>Cao</option>
                            <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Khẩn cấp</option>
                        </select>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex justify-end space-x-4 pt-6 border-t">
                        <a href="{{ url()->previous() }}" 
                           class="px-6 py-3 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition duration-200">
                            Hủy
                        </a>
                        <button type="submit" 
                                class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition duration-200">
                            <i class="fas fa-paper-plane mr-2"></i>Gửi yêu cầu
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
let productIndex = 0;
const products = @json($products);

function addProductRow() {
    const container = document.getElementById('productsContainer');
    const productRow = createProductRow(productIndex);
    container.appendChild(productRow);
    productIndex++;
}

function createProductRow(index) {
    const div = document.createElement('div');
    div.className = 'bg-gray-50 p-4 rounded-lg mb-4 product-row';
    div.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sản phẩm</label>
                <select name="products[${index}][product_id]" required 
                        class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Chọn sản phẩm</option>
                    ${products.map(product => `<option value="${product.id}">${product.name} (${product.sku})</option>`).join('')}
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Số lượng</label>
                <input type="number" name="products[${index}][quantity]" min="1" required
                       class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="0">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Lý do</label>
                <input type="text" name="products[${index}][reason]"
                       class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="Lý do...">
            </div>
            <div class="flex items-end">
                <button type="button" onclick="removeProductRow(this)" 
                        class="w-full bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-md transition duration-200">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;
    return div;
}

function removeProductRow(button) {
    const row = button.closest('.product-row');
    row.remove();
}

function updateFormLabels() {
    const type = document.getElementById('type').value;
    const productsLabel = document.getElementById('productsLabel');
    
    if (type === 'receive_request') {
        productsLabel.textContent = 'Sản phẩm muốn nhận';
    } else if (type === 'return_request') {
        productsLabel.textContent = 'Sản phẩm muốn trả';
    } else {
        productsLabel.textContent = 'Danh sách sản phẩm';
    }
}

// Thêm ít nhất một dòng sản phẩm theo mặc định
document.addEventListener('DOMContentLoaded', function() {
    addProductRow();
});

// Xác thực form
document.getElementById('notificationForm').addEventListener('submit', function(e) {
    const productRows = document.querySelectorAll('.product-row');
    if (productRows.length === 0) {
        e.preventDefault();
        alert('Vui lòng thêm ít nhất một sản phẩm.');
        return false;
    }
});
</script>
@endpush
@endsection
