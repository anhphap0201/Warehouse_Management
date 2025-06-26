<x-app-layout>
    <x-slot name="header">
        <div class="page-header-standard">
            <div class="page-header-content">
                <div class="page-title-section">
                    <h1 class="page-title-main text-gray-900">
                        <div class="page-title-icon simple-bg">
                            <i class="fas fa-tags text-green-600 text-lg"></i>
                        </div>
                        {{ __('Quản lý Danh mục') }}
                    </h1>
                    <p class="page-subtitle">Quản lý danh mục sản phẩm và phân loại</p>
                </div>
                <div class="page-actions">
                    <a href="{{ route('categories.create') }}" class="btn-primary-standard">
                        <i class="fas fa-plus mr-2"></i>
                        {{ __('Thêm Danh mục') }}
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="page-container">
        <div class="page-content">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="flash-success mb-6">
                    <div class="flash-content">
                        <i class="fas fa-check-circle flash-icon success"></i>
                        <span class="flash-text success">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="flash-error mb-6">
                    <div class="flash-content">
                        <i class="fas fa-exclamation-circle flash-icon error"></i>
                        <span class="flash-text error">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            @if($categories->count() > 0)
                <!-- Categories Table -->
                <div class="table-container">
                    
                    <div class="table-wrapper">
                        <table class="table-modern">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên danh mục</th>
                                <th>Số sản phẩm</th>
                                <th>Ngày tạo</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $category)
                                <tr>
                                    <td>
                                        <span class="text-sm font-medium text-gray-900">{{ $category->id }}</span>
                                    </td>
                                    <td>
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                                <i class="fas fa-tag text-green-600"></i>
                                            </div>
                                            <div class="font-medium text-gray-900">{{ $category->name }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            {{ $category->products_count }} sản phẩm
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-sm text-gray-500">{{ $category->created_at->format('d/m/Y') }}</span>
                                    </td>
                                    <td class="table-actions">
                                        <div class="action-buttons">
                                            <a href="{{ route('categories.show', $category) }}" 
                                               class="action-btn action-btn-view"
                                               title="Xem chi tiết">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('categories.edit', $category) }}" 
                                               class="action-btn action-btn-edit"
                                               title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('categories.destroy', $category) }}" 
                                                  method="POST" 
                                                  class="inline-block"
                                                  onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục này?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="action-btn action-btn-delete"
                                                        title="Xóa">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            @else
                <!-- Empty State -->
                <div class="card">
                    <div class="card-body">
                        <div class="text-center py-12">
                            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-tags text-gray-400 text-3xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Chưa có danh mục nào</h3>
                            <p class="text-gray-500 mb-6">Tạo danh mục đầu tiên để phân loại sản phẩm của bạn</p>
                            <a href="{{ route('categories.create') }}" class="btn btn-add-new">
                                <i class="fas fa-plus mr-2"></i>
                                Thêm danh mục đầu tiên
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
