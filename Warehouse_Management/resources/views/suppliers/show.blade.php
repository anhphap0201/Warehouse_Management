<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Chi tiết nhà cung cấp') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="w-9/12 mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">Chi tiết nhà cung cấp: {{ $supplier->name }}</h3>
                        <div class="flex space-x-2">
                            <a href="{{ route('suppliers.edit', $supplier->id) }}" 
                               class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-md transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Chỉnh sửa
                            </a>
                            <a href="{{ route('suppliers.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-md transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Quay lại
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <h4 class="text-md font-semibold mb-4 text-gray-800 dark:text-gray-200">Thông tin cơ bản</h4>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-600 dark:text-gray-400">Tên nhà cung cấp:</span>
                                    <span class="text-gray-900 dark:text-gray-100">{{ $supplier->name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-600 dark:text-gray-400">Người liên hệ:</span>
                                    <span class="text-gray-900 dark:text-gray-100">{{ $supplier->contact_person ?? 'Chưa cập nhật' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-600 dark:text-gray-400">Email:</span>
                                    <span class="text-gray-900 dark:text-gray-100">
                                        @if($supplier->email)
                                            <a href="mailto:{{ $supplier->email }}" class="text-blue-600 hover:text-blue-800">{{ $supplier->email }}</a>
                                        @else
                                            Chưa cập nhật
                                        @endif
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-600 dark:text-gray-400">Số điện thoại:</span>
                                    <span class="text-gray-900 dark:text-gray-100">
                                        @if($supplier->phone)
                                            <a href="tel:{{ $supplier->phone }}" class="text-blue-600 hover:text-blue-800">{{ $supplier->phone }}</a>
                                        @else
                                            Chưa cập nhật
                                        @endif
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-600 dark:text-gray-400">Mã số thuế:</span>
                                    <span class="text-gray-900 dark:text-gray-100">{{ $supplier->tax_number ?? 'Chưa cập nhật' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-600 dark:text-gray-400">Trạng thái:</span>
                                    <span>
                                        @if($supplier->status == 'active')
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                                Hoạt động
                                            </span>
                                        @else
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100">
                                                Không hoạt động
                                            </span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <h4 class="text-md font-semibold mb-2 text-gray-800 dark:text-gray-200">Địa chỉ</h4>
                                <p class="text-gray-900 dark:text-gray-100">
                                    {{ $supplier->address ?? 'Chưa cập nhật' }}
                                </p>
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <h4 class="text-md font-semibold mb-2 text-gray-800 dark:text-gray-200">Mô tả</h4>
                                <p class="text-gray-900 dark:text-gray-100">
                                    {{ $supplier->description ?? 'Chưa có mô tả' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-6">
                            <h4 class="text-md font-semibold mb-4 text-gray-800 dark:text-gray-200">Thông tin thời gian</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-600 dark:text-gray-400">Ngày tạo:</span>
                                    <span class="text-gray-900 dark:text-gray-100">{{ $supplier->created_at->format('d/m/Y H:i:s') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-600 dark:text-gray-400">Cập nhật lần cuối:</span>
                                    <span class="text-gray-900 dark:text-gray-100">{{ $supplier->updated_at->format('d/m/Y H:i:s') }}</span>
                                </div>
                            </div>
                        </div>

                        @if($supplier->purchaseOrders->count() > 0)
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <h4 class="text-md font-semibold mb-4 text-gray-800 dark:text-gray-200">Đơn hàng mua gần đây</h4>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                                    <thead class="bg-gray-100 dark:bg-gray-600">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Mã đơn hàng
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Ngày đặt hàng
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Tổng tiền
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Trạng thái
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Thao tác
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600">
                                        @foreach($supplier->purchaseOrders->take(5) as $order)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $order->order_number }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ $order->order_date->format('d/m/Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ number_format($order->total_amount, 0, ',', '.') }} VNĐ
                                            </td>                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @switch($order->status)
                                                    @case('confirmed')
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                                            Đã xác nhận
                                                        </span>
                                                        @break
                                                    @case('ordered')
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                                                            Đã đặt hàng
                                                        </span>
                                                        @break
                                                    @case('delivered')
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                                            Đã giao hàng
                                                        </span>
                                                        @break
                                                    @case('cancelled')
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100">
                                                            Đã hủy
                                                        </span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('purchase-orders.show', $order->id) }}" 
                                                   class="inline-flex items-center px-3 py-1 border border-blue-300 rounded-md text-blue-600 hover:text-blue-900 hover:bg-blue-50 dark:text-blue-400 dark:hover:text-blue-300 dark:border-blue-600 dark:hover:bg-blue-900 transition-all duration-200">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                    Xem
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if($supplier->purchaseOrders->count() > 5)
                                <div class="text-center mt-4">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Và {{ $supplier->purchaseOrders->count() - 5 }} đơn hàng khác...
                                    </p>
                                </div>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
