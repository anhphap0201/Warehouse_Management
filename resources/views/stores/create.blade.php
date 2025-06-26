<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Thêm Cửa hàng Mới') }}
            </h2>
            <a href="{{ route('stores.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>
                Quay lại
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="card">
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-error mb-6">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('stores.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Tên cửa hàng -->
                        <div>
                            <label for="name" class="form-label">
                                Tên Cửa hàng <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                   class="form-input @error('name') border-red-500 @enderror"
                                   placeholder="Nhập tên cửa hàng">
                            @error('name')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Địa chỉ -->
                        <div>
                            <label for="location" class="form-label">
                                Địa chỉ <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="location" id="location" value="{{ old('location') }}" required
                                   class="form-input @error('location') border-red-500 @enderror"
                                   placeholder="Ví dụ: Quận 1, TP. Hồ Chí Minh">
                            @error('location')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit buttons -->
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('stores.index') }}" 
                               class="btn btn-secondary">
                                Hủy
                            </a>
                            <button type="submit"
                                    class="btn btn-primary">
                                <i class="fas fa-save mr-2"></i>
                                Tạo Cửa hàng
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
