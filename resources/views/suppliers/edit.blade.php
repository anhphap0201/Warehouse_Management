<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Chỉnh sửa nhà cung cấp') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('suppliers.show', $supplier) }}" 
                   class="btn btn-secondary btn-sm">
                    <i class="fas fa-eye mr-2"></i>
                    Xem Chi Tiết
                </a>
                <a href="{{ route('suppliers.index') }}" 
                   class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Quay lại
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="w-9/12 mx-auto sm:px-6 lg:px-8">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title mb-6">Chỉnh sửa nhà cung cấp: {{ $supplier->name }}</h3>

                    <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-grid-2">
                            <div>
                                <label for="name" class="form-label">
                                    Tên nhà cung cấp <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $supplier->name) }}" 
                                       class="form-input @error('name') border-red-500 @enderror" 
                                       required>
                                @error('name')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="contact_person" class="form-label">
                                    Người liên hệ
                                </label>
                                <input type="text" 
                                       id="contact_person" 
                                       name="contact_person" 
                                       value="{{ old('contact_person', $supplier->contact_person) }}" 
                                       class="form-input @error('contact_person') border-red-500 @enderror">
                                @error('contact_person')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="form-grid-2">
                            <div>
                                <label for="email" class="form-label">
                                    Email
                                </label>
                                <input type="email" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', $supplier->email) }}" 
                                       class="form-input @error('email') border-red-500 @enderror"
                                       placeholder="nhacungcap@example.com">
                                @error('email')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="phone" class="form-label">
                                    Số điện thoại
                                </label>
                                <input type="text" 
                                       id="phone" 
                                       name="phone" 
                                       value="{{ old('phone', $supplier->phone) }}" 
                                       class="form-input @error('phone') border-red-500 @enderror">
                                @error('phone')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="form-grid-2">
                            <div>
                                <label for="tax_number" class="form-label">
                                    Mã số thuế
                                </label>
                                <input type="text" 
                                       id="tax_number" 
                                       name="tax_number" 
                                       value="{{ old('tax_number', $supplier->tax_number) }}" 
                                       class="form-input @error('tax_number') border-red-500 @enderror">
                                @error('tax_number')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="status" class="form-label">
                                    Trạng thái
                                </label>
                                <select id="status" 
                                        name="status" 
                                        class="form-select @error('status') border-red-500 @enderror">
                                    <option value="active" {{ old('status', $supplier->status) == 'active' ? 'selected' : '' }}>Hoạt động</option>
                                    <option value="inactive" {{ old('status', $supplier->status) == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                                </select>
                                @error('status')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="address" class="form-label">
                                Địa chỉ
                            </label>
                            <textarea id="address" 
                                      name="address" 
                                      rows="3" 
                                      class="form-input @error('address') border-red-500 @enderror">{{ old('address', $supplier->address) }}</textarea>
                            @error('address')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="form-label">
                                Mô tả
                            </label>
                            <textarea id="description" 
                                      name="description" 
                                      rows="4" 
                                      class="form-input @error('description') border-red-500 @enderror">{{ old('description', $supplier->description) }}</textarea>
                            @error('description')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Meta information -->
                        <div class="info-card p-4 border border-gray-100 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-700">
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-info-circle mr-2"></i>Thông Tin Hiện Tại
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div class="info-item">
                                    <span class="date-label">Ngày tạo:</span>
                                    <span class="ml-2 created-date">{{ $supplier->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="date-label">Cập nhật lần cuối:</span>
                                    <span class="ml-2 updated-date">{{ $supplier->updated_at->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end space-x-4 pt-6 border-t dark:border-gray-700">
                            <a href="{{ route('suppliers.index') }}" 
                               class="btn btn-secondary">
                                <i class="fas fa-times mr-2"></i>
                                Hủy
                            </a>
                            <button type="submit" 
                                    class="btn btn-primary">
                                <i class="fas fa-save mr-2"></i>
                                Cập nhật
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
