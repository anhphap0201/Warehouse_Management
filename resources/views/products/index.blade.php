<x-app-layout>
    <x-slot name="header">
        <div class="page-header-standard">
            <div class="page-header-content">
                <div class="page-title-section">
                    <h1 class="page-title-main text-gray-900">
                        <div class="page-title-icon simple-bg">
                            <i class="fas fa-box text-blue-600 text-lg"></i>
                        </div>
                        {{ __('Quản lý Sản phẩm') }}
                    </h1>
                    <p class="page-subtitle">Quản lý danh sách sản phẩm và thông tin chi tiết</p>
                </div>
                <div class="page-actions">
                    <a href="{{ route('products.create') }}" class="btn-primary-standard">
                        <i class="fas fa-plus mr-2"></i>
                        {{ __('Thêm Sản phẩm') }}
                    </a>
                </div>
            </div>
        </div>
    </x-slot>    <div class="page-container">
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
            @endif            @if($products->count() > 0)
                <!-- Products Table -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <div class="section-divider">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                                <i class="fas fa-box mr-2 text-blue-600"></i>
                                Danh sách sản phẩm
                            </h3>
                        </div>

                        <div class="table-wrapper">
                            <!-- Table View -->
                            <table class="table-modern">
                            <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th>SKU</th>
                                <th>Danh mục</th>
                                <th>Đơn vị</th>
                                <th>Tồn kho</th>
                                <th>Ngày tạo</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                                <tr>
                                    <td>
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                                <i class="fas fa-box text-blue-600"></i>
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900">{{ $product->name }}</div>
                                                @if($product->description)
                                                    <div class="text-sm text-gray-500">{{ Str::limit($product->description, 50) }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ $product->sku }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($product->category)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $product->category->name }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 text-sm">Chưa phân loại</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-sm text-gray-900">{{ $product->unit }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $totalInventory = $product->inventory->sum('quantity');
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $totalInventory > 10 ? 'bg-green-100 text-green-800' : ($totalInventory > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ $totalInventory }} {{ $product->unit }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-sm text-gray-500">{{ $product->created_at->format('d/m/Y') }}</span>
                                    </td>
                                    <td class="table-actions">
                                        <div class="action-buttons">
                                            <a href="{{ route('products.show', $product) }}" 
                                               class="action-btn action-btn-view"
                                               title="Xem chi tiết">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('products.edit', $product) }}" 
                                               class="action-btn action-btn-edit"
                                               title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('products.destroy', $product) }}" 
                                                  method="POST" 
                                                  class="inline-block"
                                                  onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">
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
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-8">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="fas fa-search text-gray-300 text-4xl mb-4"></i>
                                            <p class="text-gray-500">Không tìm thấy sản phẩm nào</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                        </div>
                        
                        <!-- Grid View (Hidden by default) -->
                        <div id="gridView" class="hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">                            @foreach($products as $product)
                                <div class="product-card border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-box text-blue-600"></i>
                                        </div>
                                        <div class="flex space-x-1">
                                            <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <h4 class="font-semibold text-gray-900 mb-2">{{ $product->name }}</h4>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex justify-between">
                                            <span class="text-gray-500">SKU:</span>
                                            <span class="font-mono bg-gray-100 px-2 py-0.5 rounded text-xs">{{ $product->sku }}</span>
                                        </div>
                                        @if($product->category)
                                            <div class="flex justify-between">
                                                <span class="text-gray-500">Danh mục:</span>
                                                <span class="text-blue-600">{{ $product->category->name }}</span>
                                            </div>
                                        @endif
                                        <div class="flex justify-between">
                                            <span class="text-gray-500">Đơn vị:</span>
                                            <span>{{ $product->unit }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-500">Tồn kho:</span>
                                            @php $totalInventory = $product->inventory->sum('quantity'); @endphp
                                            <span class="px-2 py-0.5 rounded text-xs 
                                                {{ $totalInventory > 10 ? 'bg-green-100 text-green-800' : ($totalInventory > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                {{ $totalInventory }} {{ $product->unit }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                @if($products->hasPages())
                    <div class="mt-6">
                        {{ $products->links() }}
                    </div>
                @endif

            @else
                <!-- Empty State -->
                <div class="card">
                    <div class="card-body">
                        <div class="text-center py-12">
                            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-box text-gray-400 text-3xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Chưa có sản phẩm nào</h3>
                            <p class="text-gray-500 mb-6">Bắt đầu bằng cách thêm sản phẩm đầu tiên của bạn</p>                            <a href="{{ route('products.create') }}" class="btn btn-add-new">
                                <i class="fas fa-plus mr-2"></i>
                                Thêm sản phẩm đầu tiên
                            </a>
                        </div>
                    </div>                </div>
            @endif

            @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const toggleView = document.getElementById('toggleView');
                    const tableView = document.getElementById('tableView');
                    const gridView = document.getElementById('gridView');

                    let currentView = 'table';

                    // View toggle
                    toggleView.addEventListener('click', function() {
                        if (currentView === 'table') {
                            tableView.classList.add('hidden');
                            gridView.classList.remove('hidden');
                            toggleView.innerHTML = '<i class="fas fa-table mr-1"></i>Dạng bảng';
                            toggleView.dataset.view = 'grid';
                            currentView = 'grid';
                        } else {
                            tableView.classList.remove('hidden');
                            gridView.classList.add('hidden');
                            toggleView.innerHTML = '<i class="fas fa-th-large mr-1"></i>Dạng lưới';
                            toggleView.dataset.view = 'table';
                            currentView = 'table';
                        }
                    });

                    // Delete confirmation
                    document.querySelectorAll('form[method="POST"]').forEach(form => {
                        form.addEventListener('submit', function(e) {
                            if (this.querySelector('button[type="submit"]').classList.contains('btn-danger')) {
                                if (!confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')) {
                                    e.preventDefault();
                                }
                            }
                        });
                    });
                });
            </script>
            @endpush
        </div>
    </div>
</x-app-layout>
