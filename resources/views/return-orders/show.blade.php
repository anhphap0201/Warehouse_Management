<x-app-layout>
    <x-slot name="header">
        <div class="page-header-standard">
            <div class="page-header-content">
                <div class="page-title-section">
                    <h1 class="page-title-main text-gray-900">
                        <div class="page-title-icon simple-bg">
                            <i class="fas fa-undo text-orange-600 text-lg"></i>
                        </div>
                        {{ __('Chi tiết Đơn Trả Hàng') }}
                    </h1>
                    <p class="page-subtitle">{{ $returnOrder->invoice_number }}</p>
                </div>
                <div class="page-actions">
                    <a href="{{ route('return-orders.index') }}" class="btn-secondary-standard">
                        <i class="fas fa-arrow-left mr-2"></i>
                        {{ __('Quay lại') }}
                    </a>
                    
                    @if($returnOrder->status == 'pending')
                        <form method="POST" action="{{ route('return-orders.process', $returnOrder) }}" 
                              class="inline-block"
                              onsubmit="return confirm('Bạn có chắc muốn xử lý đơn trả này? Hàng sẽ được trừ khỏi kho.')">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn-primary-standard">
                                <i class="fas fa-check mr-2"></i>
                                Xử lý đơn trả
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </x-slot>

    <div class="page-container">
        <div class="page-content">
            <div class="max-w-6xl mx-auto space-y-6">                <!-- Thông tin đơn trả hàng -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <div class="section-divider">
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                    <i class="fas fa-info-circle mr-2 text-blue-600"></i>
                                    Thông tin đơn trả hàng
                                </h3>
                                
                                <!-- Trạng thái -->
                                <div>
                                @if($returnOrder->status == 'pending')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                        <i class="fas fa-clock mr-2"></i>
                                        Chờ xử lý
                                    </span>
                                @elseif($returnOrder->status == 'processed')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        <i class="fas fa-check mr-2"></i>
                                        Đã xử lý
                                    </span>
                                @elseif($returnOrder->status == 'cancelled')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                        <i class="fas fa-times mr-2"></i>
                                        Đã hủy
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Số hóa đơn
                                </label>
                                <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                    {{ $returnOrder->invoice_number }}
                                </div>
                            </div>
                            
                            <div class="grid-divider-vertical">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Kho hàng
                                </label>
                                <div class="text-gray-900 dark:text-gray-100">
                                    {{ $returnOrder->warehouse->name }}
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Tổng tiền
                                </label>
                                <div class="text-lg font-semibold text-blue-600 dark:text-blue-400">
                                    {{ number_format($returnOrder->total_amount, 0, ',', '.') }} VNĐ
                                </div>
                            </div>
                            
                            <div class="grid-divider-horizontal">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Ngày tạo
                                </label>
                                <div class="text-gray-900 dark:text-gray-100">
                                    {{ $returnOrder->created_at->format('d/m/Y H:i') }}
                                </div>
                            </div>
                            
                            @if($returnOrder->processed_at)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Ngày xử lý
                                    </label>
                                    <div class="text-gray-900 dark:text-gray-100">
                                        {{ $returnOrder->processed_at->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>                <!-- Thông tin nhà cung cấp -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <div class="section-divider">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                                <i class="fas fa-truck mr-2 text-green-600"></i>
                                Thông tin nhà cung cấp
                            </h3>
                        </div>
                          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="grid-divider-vertical">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Tên nhà cung cấp
                                </label>
                                <div class="text-gray-900 dark:text-gray-100">
                                    {{ $returnOrder->supplier_name }}
                                </div>
                            </div>
                            
                            @if($returnOrder->supplier_phone)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Số điện thoại
                                    </label>
                                    <div class="text-gray-900 dark:text-gray-100">
                                        {{ $returnOrder->supplier_phone }}
                                    </div>
                                </div>
                            @endif
                            
                            @if($returnOrder->supplier_address)
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Địa chỉ
                                    </label>
                                    <div class="text-gray-900 dark:text-gray-100">
                                        {{ $returnOrder->supplier_address }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>                <!-- Lý do trả hàng -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <div class="section-divider">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                                <i class="fas fa-exclamation-triangle mr-2 text-yellow-600"></i>
                                Lý do trả hàng
                            </h3>
                        </div>
                        <div class="text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            {{ $returnOrder->return_reason }}
                        </div>
                    </div>
                </div>

                <!-- Danh sách sản phẩm -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <div class="section-divider">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                                <i class="fas fa-box mr-2 text-orange-600"></i>
                                Danh sách sản phẩm trả hàng
                            </h3>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="table-modern w-full">
                                <thead>
                                    <tr>
                                        <th class="text-left">Sản phẩm</th>
                                        <th class="text-left">SKU</th>
                                        <th class="text-right">Số lượng</th>
                                        <th class="text-right">Đơn giá</th>
                                        <th class="text-right">Thành tiền</th>
                                        <th class="text-left">Lý do trả</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($returnOrder->items as $item)
                                        <tr>
                                            <td>
                                                <div class="font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $item->product->name }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-gray-700 dark:text-gray-300">
                                                    {{ $item->product->sku }}
                                                </div>
                                            </td>
                                            <td class="text-right">
                                                <div class="font-medium text-gray-900 dark:text-gray-100">
                                                    {{ number_format($item->quantity, 0, ',', '.') }}
                                                </div>
                                            </td>
                                            <td class="text-right">
                                                <div class="text-gray-900 dark:text-gray-100">
                                                    {{ number_format($item->unit_price, 0, ',', '.') }} VNĐ
                                                </div>
                                            </td>
                                            <td class="text-right">
                                                <div class="font-medium text-gray-900 dark:text-gray-100">
                                                    {{ number_format($item->total_price, 0, ',', '.') }} VNĐ
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-sm text-gray-700 dark:text-gray-300">
                                                    {{ $item->return_reason ?: '-' }}
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>                                <tfoot>
                                    <tr class="bg-gray-50 dark:bg-gray-700 table-section-header">
                                        <td colspan="4" class="text-right font-semibold text-gray-900 dark:text-gray-100 px-6 py-3">
                                            Tổng cộng:
                                        </td>
                                        <td class="text-right font-semibold text-blue-600 dark:text-blue-400 px-6 py-3">
                                            {{ number_format($returnOrder->total_amount, 0, ',', '.') }} VNĐ
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>                <!-- Ghi chú -->
                @if($returnOrder->notes)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                        <div class="p-6">
                            <div class="section-divider">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                                    <i class="fas fa-sticky-note mr-2 text-green-600"></i>
                                    Ghi chú
                                </h3>
                            </div>
                            <div class="text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                {{ $returnOrder->notes }}
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Actions cho đơn chờ xử lý -->
                @if($returnOrder->status == 'pending')
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                        <div class="p-6">
                            <div class="section-divider">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                                    <i class="fas fa-cogs mr-2 text-purple-600"></i>
                                    Thao tác
                                </h3>
                            </div>
                            
                            <div class="flex flex-wrap gap-4">
                                <form method="POST" action="{{ route('return-orders.process', $returnOrder) }}" 
                                      onsubmit="return confirm('Bạn có chắc muốn xử lý đơn trả này? Hàng sẽ được trừ khỏi kho.')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn-primary-standard">
                                        <i class="fas fa-check mr-2"></i>
                                        Xử lý đơn trả (Trừ hàng khỏi kho)
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('return-orders.cancel', $returnOrder) }}" 
                                      onsubmit="return confirm('Bạn có chắc muốn hủy đơn trả này?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                                        <i class="fas fa-ban mr-2"></i>
                                        Hủy đơn trả
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('return-orders.destroy', $returnOrder) }}" 
                                      onsubmit="return confirm('Bạn có chắc muốn xóa đơn trả này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                                        <i class="fas fa-trash mr-2"></i>
                                        Xóa đơn trả
                                    </button>
                                </form>
                            </div>
                            
                            <div class="mt-4 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                                <div class="flex items-start">
                                    <i class="fas fa-info-circle text-yellow-600 dark:text-yellow-400 mt-0.5 mr-3"></i>
                                    <div class="text-sm text-yellow-800 dark:text-yellow-200">
                                        <strong>Lưu ý:</strong> Đơn trả hàng này chưa được xử lý. Hàng hóa vẫn còn trong kho. 
                                        Khi bạn nhấn "Xử lý đơn trả", hệ thống sẽ tự động trừ số lượng hàng tương ứng khỏi kho.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($returnOrder->status == 'processed')
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                        <div class="p-6">
                            <div class="p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                                <div class="flex items-start">
                                    <i class="fas fa-check-circle text-green-600 dark:text-green-400 mt-0.5 mr-3"></i>
                                    <div class="text-sm text-green-800 dark:text-green-200">
                                        <strong>Đã xử lý:</strong> Đơn trả hàng này đã được xử lý vào ngày {{ $returnOrder->processed_at->format('d/m/Y H:i') }}. 
                                        Hàng hóa đã được trừ khỏi kho.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
