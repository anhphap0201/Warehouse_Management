<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Chỉnh Sửa Kho Hàng') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('warehouses.show', $warehouse) }}" 
                   class="btn btn-secondary btn-sm">
                    <i class="fas fa-eye mr-2"></i>
                    Xem Chi Tiết
                </a>
                <a href="{{ route('warehouses.index') }}" 
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
                    <h3 class="card-title mb-6">{{ __('Cập Nhật Thông Tin Kho Hàng') }}</h3>

                    @if($errors->any())
                        <div class="alert alert-error mb-6">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('warehouses.update', $warehouse) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="form-grid-2">
                            <!-- Tên Kho -->
                            <div>
                                <label for="name" class="form-label">
                                    Tên Kho <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="name" 
                                       id="name" 
                                       value="{{ old('name', $warehouse->name) }}"
                                       required
                                       class="form-input @error('name') border-red-500 @enderror"
                                       placeholder="Nhập tên kho hàng">
                                @error('name')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Địa Chỉ -->
                            <div>
                                <label for="location" class="form-label">
                                    Địa Chỉ
                                </label>
                                <input type="text" 
                                       name="location" 
                                       id="location" 
                                       value="{{ old('location', $warehouse->location) }}"
                                       class="form-input @error('location') border-red-500 @enderror"
                                       placeholder="Nhập địa chỉ kho (tùy chọn)">
                                @error('location')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Thông tin bổ sung -->
                        <div class="info-card p-4 border border-gray-100 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-700">
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-info-circle mr-2"></i>Thông Tin Hiện Tại
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div class="info-item">
                                    <span class="text-gray-500 dark:text-gray-400">Ngày tạo:</span>
                                    <span class="ml-2 font-medium">{{ $warehouse->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="text-gray-500 dark:text-gray-400">Cập nhật lần cuối:</span>
                                    <span class="ml-2 font-medium">{{ $warehouse->updated_at->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="flex justify-end space-x-4 pt-6 border-t dark:border-gray-700">
                            <a href="{{ route('warehouses.show', $warehouse) }}" 
                               class="btn btn-secondary">
                                Hủy
                            </a>
                            <button type="submit" 
                                    class="btn btn-primary">
                                <i class="fas fa-save mr-2"></i>
                                Cập Nhật Kho
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
