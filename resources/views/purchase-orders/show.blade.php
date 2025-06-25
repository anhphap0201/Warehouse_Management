<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Chi tiết hóa đơn nhập kho') }} #{{ $purchaseOrder->invoice_number }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('purchase-orders.index') }}" 
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
                    <h3 class="card-title mb-6">Chi tiết hóa đơn nhập kho #{{ $purchaseOrder->invoice_number }}</h3>

                    @if(session('success'))
                        <div class="alert alert-success mb-6">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-error mb-6">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Thông tin hóa đơn -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="info-card">
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">
                                <i class="fas fa-info-circle mr-2"></i>Thông tin cơ bản
                            </h4>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium">Số hóa đơn:</span>
                                    <span class="text-sm">{{ $purchaseOrder->invoice_number }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium">Kho hàng:</span>
                                    <span class="text-sm">{{ $purchaseOrder->warehouse->name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium">Trạng thái:</span>
                                    <span class="text-sm">
                                        @if($purchaseOrder->status == 'confirmed')
                                            <span class="badge badge-success">
                                                Đã xác nhận
                                            </span>
                                        @else
                                            <span class="badge badge-info">
                                                Hoàn thành
                                            </span>
                                        @endif
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium">Ngày tạo:</span>
                                    <span class="text-sm">{{ $purchaseOrder->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                @if($purchaseOrder->updated_at != $purchaseOrder->created_at)
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium">Cập nhật lần cuối:</span>
                                    <span class="text-sm">{{ $purchaseOrder->updated_at->format('d/m/Y H:i') }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="info-card">
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">
                                <i class="fas fa-user mr-2"></i>Thông tin nhà cung cấp
                            </h4>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium">Tên:</span>
                                    <span class="text-sm">{{ $purchaseOrder->supplier_name }}</span>
                                </div>
                                @if($purchaseOrder->supplier_phone)
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium">Điện thoại:</span>
                                    <span class="text-sm">{{ $purchaseOrder->supplier_phone }}</span>
                                </div>
                                @endif
                                @if($purchaseOrder->supplier_address)
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium">Địa chỉ:</span>
                                    <span class="text-sm">{{ $purchaseOrder->supplier_address }}</span>
                                </div>
                                @endif
                            </div>
                            
                            @if($purchaseOrder->notes)
                                <div class="mt-4">
                                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        <i class="fas fa-sticky-note mr-2"></i>Ghi chú
                                    </h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $purchaseOrder->notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="border-t border-gray-200 dark:border-gray-700 my-6"></div>

                    <!-- Chi tiết sản phẩm -->
                    <div class="mb-6">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                            <i class="fas fa-boxes mr-2"></i>Chi tiết sản phẩm
                        </h4>
                        <div class="overflow-x-auto">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th class="w-12">
                                            #
                                        </th>
                                        <th>
                                            Sản phẩm
                                        </th>
                                        <th class="w-32">
                                            Số lượng
                                        </th>
                                        <th class="w-32">
                                            Đơn giá
                                        </th>
                                        <th class="w-32">
                                            Thành tiền
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($purchaseOrder->items as $index => $item)
                                        <tr>
                                            <td>
                                                {{ $index + 1 }}
                                            </td>
                                            <td>
                                                <div class="font-medium">
                                                    {{ $item->product->name }}
                                                </div>
                                                @if($item->product->sku)
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                                        SKU: {{ $item->product->sku }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                {{ number_format($item->quantity) }}
                                            </td>
                                            <td>
                                                {{ number_format($item->unit_price, 0, ',', '.') }} VNĐ
                                            </td>
                                            <td class="font-medium">
                                                {{ number_format($item->total_price, 0, ',', '.') }} VNĐ
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4" class="text-right">
                                            Tổng cộng:
                                        </th>
                                        <th class="font-bold text-primary">
                                            {{ number_format($purchaseOrder->total_amount, 0, ',', '.') }} VNĐ
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>                    <!-- Thông báo trạng thái - tất cả hóa đơn đều được tự động xác nhận -->
                    <div class="mt-6">
                        <div class="alert alert-success">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle mr-2"></i>
                                <span>Hóa đơn đã được xác nhận và các sản phẩm đã được cập nhật vào kho hàng.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
