<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="page-title flex items-center">
                    <i class="fas fa-plus mr-3 text-blue-600"></i>
                    {{ __('Thêm Sản phẩm Mới') }}
                </h1>
                <p class="page-subtitle">Tạo sản phẩm mới cho hệ thống quản lý kho</p>
            </div>
            <a href="{{ route('products.index') }}" class="btn btn-outline">
                <i class="fas fa-arrow-left mr-2"></i>
                {{ __('Quay lại') }}
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-info-circle mr-2 text-blue-600"></i>
                    {{ __('Thông tin Sản phẩm') }}
                </h3>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-error mb-6">
                        <div class="flex">
                            <i class="fas fa-exclamation-circle mr-3 text-red-500"></i>
                            <div>
                                <strong>Có lỗi xảy ra:</strong>
                                <ul class="mt-2 list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('products.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <!-- Product Information Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Product Name -->
                        <div class="form-group">
                            <label for="name" class="form-label">
                                Tên sản phẩm <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="{{ old('name') }}" 
                                   class="form-input" 
                                   placeholder="Nhập tên sản phẩm..."
                                   required>
                            @error('name')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- SKU -->
                        <div class="form-group">
                            <label for="sku" class="form-label">
                                Mã SKU <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="text" 
                                       name="sku" 
                                       id="sku" 
                                       value="{{ old('sku') }}" 
                                       class="form-input pr-10" 
                                       placeholder="Nhập mã SKU..."
                                       required>
                                <button type="button" 
                                        id="generateSKU" 
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
                                        title="Tự động tạo SKU">
                                    <i class="fas fa-random"></i>
                                </button>
                            </div>
                            @error('sku')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <!-- Category -->
                        <div class="form-group">
                            <label for="category_id" class="form-label">
                                Danh mục <span class="text-red-500">*</span>
                            </label>
                            <select name="category_id" 
                                    id="category_id" 
                                    class="form-select"
                                    required>
                                <option value="">Chọn danh mục</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                            {{ old('category_id', request('category_id')) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Unit -->
                        <div class="form-group">
                            <label for="unit" class="form-label">
                                Đơn vị <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="unit" 
                                   id="unit" 
                                   value="{{ old('unit') }}" 
                                   class="form-input" 
                                   placeholder="VD: Cái, Kg, Lít..."
                                   required>
                            @error('unit')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="form-group">
                        <label for="description" class="form-label">
                            Mô tả sản phẩm
                        </label>
                        <textarea name="description" 
                                  id="description" 
                                  rows="4" 
                                  class="form-textarea" 
                                  placeholder="Nhập mô tả chi tiết về sản phẩm...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('products.index') }}" class="btn btn-outline">
                            <i class="fas fa-times mr-2"></i>
                            Hủy bỏ
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-2"></i>
                            Lưu sản phẩm
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const generateSKUBtn = document.getElementById('generateSKU');
            const skuInput = document.getElementById('sku');
            const nameInput = document.getElementById('name');

            // Auto-generate SKU
            generateSKUBtn.addEventListener('click', function() {
                const name = nameInput.value.trim();
                if (name) {
                    // Create SKU from product name + timestamp
                    const namePrefix = name.replace(/[^a-zA-Z0-9]/g, '').toUpperCase().slice(0, 3);
                    const timestamp = Date.now().toString().slice(-6);
                    const generatedSKU = namePrefix + timestamp;
                    skuInput.value = generatedSKU;
                } else {
                    // Generate random SKU
                    const randomSKU = 'PRD' + Math.random().toString(36).substr(2, 9).toUpperCase();
                    skuInput.value = randomSKU;
                }
            });

            // Auto-suggest SKU when name changes
            nameInput.addEventListener('blur', function() {
                if (this.value.trim() && !skuInput.value) {
                    const namePrefix = this.value.replace(/[^a-zA-Z0-9]/g, '').toUpperCase().slice(0, 3);
                    const timestamp = Date.now().toString().slice(-6);
                    skuInput.value = namePrefix + timestamp;
                }
            });

            // Form validation
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                const requiredFields = form.querySelectorAll('[required]');
                let isValid = true;

                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        isValid = false;
                        field.classList.add('border-red-500');
                    } else {
                        field.classList.remove('border-red-500');
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    alert('Vui lòng điền đầy đủ các trường bắt buộc!');
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
