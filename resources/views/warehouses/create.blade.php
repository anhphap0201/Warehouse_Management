<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Thêm Kho Hàng Mới') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="w-9/12 mx-auto sm:px-6 lg:px-8">
            <div class="bg-transparent dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">{{ __('Thông tin Kho Hàng') }}</h3>
                        <a href="{{ route('warehouses.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-md transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Quay lại
                        </a>
                    </div>

                    @if($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('warehouses.store') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <div class="form-grid-2">
                            <!-- Tên kho -->
                            <div>
                                <label for="name" class="form-label">
                                    Tên Kho Hàng <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" id="name" 
                                       value="{{ old('name') }}"
                                       class="form-input @error('name') border-red-500 @enderror"
                                       placeholder="Nhập tên kho hàng" required>
                                @error('name')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Địa chỉ -->
                            <div>
                                <label for="location" class="form-label">
                                    Địa Chỉ
                                </label>
                                <input type="text" name="location" id="location" 
                                       value="{{ old('location') }}"
                                       class="form-input @error('location') border-red-500 @enderror"
                                       placeholder="Nhập địa chỉ kho hàng">
                                @error('location')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('warehouses.index') }}" 
                               class="btn btn-secondary">
                                Hủy
                            </a>
                            <button type="submit" 
                                    class="btn btn-primary">
                                <i class="fas fa-plus mr-2"></i>
                                Tạo Kho Hàng
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
