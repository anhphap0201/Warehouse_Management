<x-app-layout>    <x-slot name="header">
        <div class="page-header-standard">
            <div class="page-header-content">
                <div class="page-title-section">
                    <h1 class="page-title-main text-gray-900">
                        <div class="page-title-icon simple-bg">
                            <i class="fas fa-warehouse text-blue-600 text-lg"></i>
                        </div>
                        {{ __('Quản lý Kho Hàng') }}
                    </h1>
                    <p class="page-subtitle">
                        Quản lý và theo dõi tất cả các kho hàng trong hệ thống
                    </p>
                </div>
                <div class="page-actions">
                    <a href="{{ route('warehouses.create') }}" class="btn-primary-standard">
                        <i class="fas fa-plus mr-2"></i>
                        {{ __('Thêm Kho Mới') }}
                    </a>
                </div>
            </div>
        </div>
    </x-slot><div class="page-container">
        <div class="page-content">
            <!-- Success Message -->
            @if(session('success'))
                <div class="flash-success mb-8">
                    <div class="flash-content">
                        <i class="fas fa-check-circle flash-icon success"></i>
                        <span class="flash-text success">{{ session('success') }}</span>
                    </div>
                </div>
            @endif            <!-- Warehouses Table -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <div class="section-divider">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                            <i class="fas fa-warehouse mr-2 text-green-600"></i>
                            Danh sách kho hàng
                        </h3>
                    </div>

                    <div class="table-wrapper">
                        @if($warehouses->count() > 0)
                            <!-- Table View -->
                            <table class="table-modern">
                            <thead>
                                <tr>
                                    <th>Tên Kho</th>
                                    <th>Vị Trí</th>
                                    <th>Sản Phẩm</th>
                                    <th>Tổng SL</th>
                                    <th>Ngày tạo</th>
                                    <th class="text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($warehouses as $warehouse)
                                    <tr>
                                        <td>
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                                    <i class="fas fa-warehouse text-blue-600"></i>
                                                </div>
                                                <div>
                                                    <div class="font-medium text-gray-900">{{ $warehouse->name }}</div>
                                                    <div class="text-sm text-gray-500">ID: #{{ $warehouse->id }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-sm text-gray-900">
                                                @if($warehouse->location)
                                                    <i class="fas fa-map-marker-alt text-gray-400 mr-2"></i>
                                                    {{ $warehouse->location }}
                                                @else
                                                    <span class="text-gray-400">Chưa có</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $warehouse->inventory->count() }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                {{ $warehouse->inventory->sum('quantity') }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="text-sm text-gray-500">
                                                <div>{{ $warehouse->created_at->format('d/m/Y') }}</div>
                                                <div class="text-xs text-gray-400">{{ $warehouse->created_at->format('H:i') }}</div>
                                            </div>
                                        </td>                                        <td class="table-actions">
                                            <div class="action-buttons">
                                                <a href="{{ route('warehouses.show', $warehouse) }}" 
                                                   class="action-btn action-btn-view"
                                                   title="Xem chi tiết">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('warehouses.edit', $warehouse) }}" 
                                                   class="action-btn action-btn-edit"
                                                   title="Chỉnh sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('warehouses.destroy', $warehouse) }}" 
                                                      method="POST" 
                                                      class="inline-block"
                                                      onsubmit="return confirm('Bạn có chắc chắn muốn xóa kho này không?')">
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
                            </tbody>                        </table>

                        <!-- Pagination -->
                        @if($warehouses->hasPages())
                            <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                                {{ $warehouses->links() }}
                            </div>
                        @endif                    @else
                        <div class="text-center py-16">
                            <div class="mx-auto h-20 w-20 text-gray-400 mb-6">
                                <i class="fas fa-warehouse text-6xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-2">Chưa có kho hàng nào</h3>
                            <p class="text-gray-500 dark:text-gray-400 mb-6">
                                Bắt đầu bằng cách tạo kho hàng đầu tiên của bạn.
                            </p>
                            <a href="{{ route('warehouses.create') }}" 
                               class="inline-flex items-center px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl shadow-lg transition-all duration-300 transform hover:scale-105">
                                <i class="fas fa-plus mr-2"></i>
                                Tạo Kho Hàng Đầu Tiên
                            </a>
                        </div>
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
