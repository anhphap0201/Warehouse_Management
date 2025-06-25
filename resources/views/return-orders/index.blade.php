<x-app-layout>
    <x-slot name="header">
        <div class="page-header-standard">
            <div class="page-header-content">
                <div class="page-title-section">
                    <h1 class="page-title-main text-gray-900">
                        <div class="page-title-icon simple-bg">
                            <i class="fas fa-undo text-orange-600 text-lg"></i>
                        </div>
                        {{ __('Quản lý Đơn Trả Hàng') }}
                    </h1>
                    <p class="page-subtitle">Danh sách các đơn trả hàng trong hệ thống</p>
                </div>
                <div class="page-actions">
                    <a href="{{ route('return-orders.create') }}" class="btn-add-new">
                        <i class="fas fa-plus mr-2"></i>
                        {{ __('Tạo đơn trả hàng') }}
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="page-container">
        <div class="page-content">            <!-- Bộ lọc -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
                <div class="p-6">
                    <div class="section-divider">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                            <i class="fas fa-filter mr-2 text-blue-600"></i>
                            Bộ lọc tìm kiếm
                        </h3>
                    </div>
                    <form method="GET" action="{{ route('return-orders.index') }}" class="space-y-4">                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="grid-divider-vertical">
                                <label for="invoice_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Số hóa đơn
                                </label>
                                <input type="text" name="invoice_number" id="invoice_number" 
                                       value="{{ $filters['invoice_number'] }}"
                                       placeholder="Tìm theo số hóa đơn..."
                                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-300">
                            </div>
                            
                            <div class="grid-divider-vertical">
                                <label for="warehouse" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Kho hàng
                                </label>
                                <select name="warehouse" id="warehouse"
                                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-300">
                                    <option value="">Tất cả kho</option>
                                    @foreach($warehouses as $warehouse)
                                        <option value="{{ $warehouse->name }}" {{ $filters['warehouse'] == $warehouse->name ? 'selected' : '' }}>
                                            {{ $warehouse->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="grid-divider-vertical">
                                <label for="supplier" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Nhà cung cấp
                                </label>
                                <input type="text" name="supplier" id="supplier" 
                                       value="{{ $filters['supplier'] }}"
                                       placeholder="Tìm theo nhà cung cấp..."
                                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-300">
                            </div>
                            
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Trạng thái
                                </label>
                                <select name="status" id="status"
                                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-300">
                                    <option value="">Tất cả trạng thái</option>
                                    <option value="pending" {{ $filters['status'] == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                    <option value="processed" {{ $filters['status'] == 'processed' ? 'selected' : '' }}>Đã xử lý</option>
                                    <option value="cancelled" {{ $filters['status'] == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex justify-end space-x-3">
                            <a href="{{ route('return-orders.index') }}" class="btn-secondary-standard">
                                <i class="fas fa-times mr-2"></i>
                                Xóa bộ lọc
                            </a>
                            <button type="submit" class="btn-primary-standard">
                                <i class="fas fa-search mr-2"></i>
                                Tìm kiếm
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Danh sách đơn trả hàng -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                @if($returnOrders->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="table-modern w-full">
                            <thead>
                                <tr>
                                    <th class="text-left">Số hóa đơn</th>
                                    <th class="text-left">Kho hàng</th>
                                    <th class="text-left">Nhà cung cấp</th>
                                    <th class="text-left">Tổng tiền</th>
                                    <th class="text-left">Trạng thái</th>
                                    <th class="text-left">Ngày tạo</th>
                                    <th class="text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($returnOrders as $returnOrder)
                                    <tr>
                                        <td>
                                            <div class="font-medium text-gray-900 dark:text-gray-100">
                                                {{ $returnOrder->invoice_number }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-gray-700 dark:text-gray-300">
                                                {{ $returnOrder->warehouse->name }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-gray-700 dark:text-gray-300">
                                                {{ $returnOrder->supplier_name }}
                                            </div>
                                            @if($returnOrder->supplier_phone)
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $returnOrder->supplier_phone }}
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="font-medium text-gray-900 dark:text-gray-100">
                                                {{ number_format($returnOrder->total_amount, 0, ',', '.') }} VNĐ
                                            </div>
                                        </td>
                                        <td>
                                            @if($returnOrder->status == 'pending')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                    <i class="fas fa-clock mr-1"></i>
                                                    Chờ xử lý
                                                </span>
                                            @elseif($returnOrder->status == 'processed')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                    <i class="fas fa-check mr-1"></i>
                                                    Đã xử lý
                                                </span>
                                            @elseif($returnOrder->status == 'cancelled')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                    <i class="fas fa-times mr-1"></i>
                                                    Đã hủy
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="text-gray-700 dark:text-gray-300">
                                                {{ $returnOrder->created_at->format('d/m/Y') }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $returnOrder->created_at->format('H:i') }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="flex justify-center space-x-2">
                                                <!-- Xem chi tiết -->                                                <a href="{{ route('return-orders.show', $returnOrder) }}" 
                                                   class="action-btn action-btn-view"
                                                   title="Xem chi tiết">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                @if($returnOrder->status == 'pending')
                                                    <div class="action-divider"></div>
                                                    
                                                    <!-- Xử lý đơn trả -->
                                                    <form method="POST" action="{{ route('return-orders.process', $returnOrder) }}" 
                                                          class="inline-block"
                                                          onsubmit="return confirm('Bạn có chắc muốn xử lý đơn trả này? Hàng sẽ được trừ khỏi kho.')">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" 
                                                                class="action-btn action-btn-success"
                                                                title="Xử lý đơn trả">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>

                                                    <!-- Hủy đơn trả -->
                                                    <form method="POST" action="{{ route('return-orders.cancel', $returnOrder) }}" 
                                                          class="inline-block"
                                                          onsubmit="return confirm('Bạn có chắc muốn hủy đơn trả này?')">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" 
                                                                class="action-btn action-btn-warning"
                                                                title="Hủy đơn trả">
                                                            <i class="fas fa-ban"></i>
                                                        </button>
                                                    </form>
                                                @endif

                                                @if($returnOrder->status != 'processed')
                                                    <div class="action-divider"></div>
                                                    
                                                    <!-- Xóa -->
                                                    <form method="POST" action="{{ route('return-orders.destroy', $returnOrder) }}" 
                                                          class="inline-block"
                                                          onsubmit="return confirm('Bạn có chắc muốn xóa đơn trả này?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="action-btn action-btn-danger"
                                                                title="Xóa đơn trả">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($returnOrders->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                            {{ $returnOrders->links() }}
                        </div>
                    @endif
                @else
                    <!-- Empty state -->
                    <div class="text-center py-12">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full mb-4">
                            <i class="fas fa-undo text-2xl text-gray-400 dark:text-gray-500"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                            Chưa có đơn trả hàng nào
                        </h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-6">
                            Bắt đầu bằng cách tạo đơn trả hàng đầu tiên.
                        </p>
                        <a href="{{ route('return-orders.create') }}" class="btn-primary-standard">
                            <i class="fas fa-plus mr-2"></i>
                            Tạo đơn trả hàng
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
