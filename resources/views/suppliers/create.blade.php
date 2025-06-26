<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Thêm nhà cung cấp mới') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="w-9/12 mx-auto sm:px-6 lg:px-8">
            <div class="bg-transparent dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">Thêm nhà cung cấp mới</h3>
                        <a href="{{ route('suppliers.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-md transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Quay lại
                        </a>
                    </div>

                    <form action="{{ route('suppliers.store') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <div class="form-grid-2">
                            <div>
                                <label for="name" class="form-label">
                                    Tên nhà cung cấp <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}" 
                                       class="form-input @error('name') border-red-500 @enderror" 
                                       placeholder="Nhập tên nhà cung cấp"
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
                                       value="{{ old('contact_person') }}" 
                                       class="form-input @error('contact_person') border-red-500 @enderror"
                                       placeholder="Nhập tên người liên hệ">
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
                                       value="{{ old('email') }}" 
                                       class="form-input @error('email') border-red-500 @enderror"
                                       placeholder="nhacungcap@email.com">
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
                                       value="{{ old('phone') }}" 
                                       class="form-input @error('phone') border-red-500 @enderror"
                                       placeholder="0123456789">
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
                                       value="{{ old('tax_number') }}" 
                                       class="form-input @error('tax_number') border-red-500 @enderror"
                                       placeholder="Nhập mã số thuế">
                                @error('tax_number')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="status" class="form-label">
                                    Trạng thái <span class="text-red-500">*</span>
                                </label>
                                <select id="status" 
                                        name="status" 
                                        class="form-select @error('status') border-red-500 @enderror" 
                                        required>
                                    <option value="">Chọn trạng thái</option>
                                    <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Hoạt động</option>
                                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Ngừng hoạt động</option>
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
                                      class="form-input @error('address') border-red-500 @enderror"
                                      placeholder="Nhập địa chỉ nhà cung cấp">{{ old('address') }}</textarea>
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
                                      rows="3" 
                                      class="form-input @error('description') border-red-500 @enderror"
                                      placeholder="Mô tả thêm về nhà cung cấp">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end space-x-4">
                            <button type="reset" 
                                    class="btn btn-secondary">
                                <i class="fas fa-undo mr-2"></i>
                                Reset
                            </button>
                            <button type="submit" 
                                    class="btn btn-success">
                                <i class="fas fa-save mr-2"></i>
                                Lưu nhà cung cấp
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
