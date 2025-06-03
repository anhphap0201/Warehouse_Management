<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Chi tiết hóa đơn nhập kho') }} #{{ $purchaseOrder->invoice_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">Chi tiết hóa đơn nhập kho #{{ $purchaseOrder->invoice_number }}</h3>
                        <div class="flex space-x-2">
                            <a href="{{ route('purchase-orders.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-md transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Quay lại
                            </a>
                            @if($purchaseOrder->isPending())
                                <a href="{{ route('purchase-orders.edit', $purchaseOrder) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-md transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Chỉnh sửa
                                </a>
                                <form action="{{ route('purchase-orders.confirm', $purchaseOrder) }}" 
                                      method="POST" class="inline"
                                      onsubmit="return confirm('Bạn có chắc chắn muốn xác nhận hóa đơn này? Sau khi xác nhận sẽ không thể chỉnh sửa.')">
                                    @csrf
                                    <button type="submit" 
                                            class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Xác nhận hóa đơn
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Thông tin hóa đơn -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-4">Thông tin cơ bản</h4>
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
                                        @if($purchaseOrder->status == 'pending')
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100">
                                                Chờ xử lý
                                            </span>
                                        @elseif($purchaseOrder->status == 'confirmed')
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                                Đã xác nhận
                                            </span>
                                        @else
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
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
                        
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-4">Thông tin nhà cung cấp</h4>
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
                                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Ghi chú</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $purchaseOrder->notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="border-t border-gray-200 dark:border-gray-700 my-6"></div>

                    <!-- Chi tiết sản phẩm -->
                    <div class="mb-6">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Chi tiết sản phẩm</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 border border-gray-200 dark:border-gray-700 rounded-lg">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-12">
                                            #
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Sản phẩm
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-32">
                                            Số lượng
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-32">
                                            Đơn giá
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-32">
                                            Thành tiền
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($purchaseOrder->items as $index => $item)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $index + 1 }}
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $item->product->name }}
                                                </div>
                                                @if($item->product->sku)
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                                        SKU: {{ $item->product->sku }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ number_format($item->quantity) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ number_format($item->unit_price, 0, ',', '.') }} VNĐ
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ number_format($item->total_price, 0, ',', '.') }} VNĐ
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th colspan="4" class="px-6 py-3 text-right text-sm font-medium text-gray-900 dark:text-gray-100">
                                            Tổng cộng:
                                        </th>
                                        <th class="px-6 py-3 text-left text-sm font-bold text-gray-900 dark:text-gray-100">
                                            {{ number_format($purchaseOrder->total_amount, 0, ',', '.') }} VNĐ
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Nút hành động bổ sung -->
                    @if($purchaseOrder->isPending())
                        <div class="mt-6">
                            <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-md">
                                <div class="flex">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Hóa đơn này đang ở trạng thái chờ xử lý. Bạn có thể chỉnh sửa hoặc xác nhận để cập nhật vào kho.</span>
                                </div>
                            </div>
                        </div>
                    @elseif($purchaseOrder->isConfirmed())
                        <div class="mt-6">
                            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md">
                                <div class="flex">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Hóa đơn đã được xác nhận và các sản phẩm đã được cập nhật vào kho hàng.</span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
