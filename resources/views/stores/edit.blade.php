<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Chỉnh sửa Cửa hàng: ') . $store->name }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('stores.show', $store) }}" 
                   class="btn btn-secondary btn-sm">
                    <i class="fas fa-eye mr-2"></i>
                    Xem Chi Tiết
                </a>
                <a href="{{ route('stores.index') }}" 
                   class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Quay lại
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title mb-6">{{ __('Cập nhật thông tin cửa hàng') }}</h3>
                    
                    @if($errors->any())
                        <div class="alert alert-error mb-6">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form action="{{ route('stores.update', $store) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Tên cửa hàng -->
                        <div>
                            <label for="name" class="form-label">
                                Tên Cửa hàng <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name', $store->name) }}" required
                                   class="form-input @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Địa chỉ -->
                        <div>
                            <label for="location" class="form-label">
                                Địa chỉ <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="location" id="location" value="{{ old('location', $store->location) }}" required
                                   class="form-input @error('location') border-red-500 @enderror"
                                   placeholder="Ví dụ: Quận 1, TP. Hồ Chí Minh">
                            @error('location')
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
                                    <span class="text-gray-500 dark:text-gray-400">Ngày tạo:</span>
                                    <span class="ml-2 font-medium">{{ $store->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="text-gray-500 dark:text-gray-400">Cập nhật lần cuối:</span>
                                    <span class="ml-2 font-medium">{{ $store->updated_at->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Submit buttons -->
                        <div class="flex justify-end space-x-3 pt-4 border-t dark:border-gray-700">
                            <a href="{{ route('stores.index') }}" 
                               class="btn btn-secondary">
                                <i class="fas fa-times mr-2"></i>
                                Hủy
                            </a>
                            <button type="submit" 
                                    class="btn btn-primary">
                                <i class="fas fa-save mr-2"></i>
                                Cập nhật Cửa hàng
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
