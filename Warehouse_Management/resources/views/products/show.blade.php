<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Chi tiết Sản phẩm') }} - {{ $product->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="w-9/12 mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $product->name }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                SKU: <span class="font-mono bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">{{ $product->sku }}</span>
                            </p>
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('products.edit', $product) }}" 
                               class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Chỉnh sửa
                            </a>
                            <a href="{{ route('products.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-md">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Quay lại
                            </a>
                        </div>
                    </div>

                    <!-- Thông tin cơ bản -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-3">Thông tin sản phẩm</h4>
                            <dl class="space-y-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tên sản phẩm:</dt>
                                    <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $product->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">SKU:</dt>
                                    <dd class="text-sm text-gray-900 dark:text-gray-100 font-mono">{{ $product->sku }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Danh mục:</dt>
                                    <dd class="text-sm text-gray-900 dark:text-gray-100">
                                        @if($product->category)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                                {{ $product->category->name }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">Chưa phân loại</span>
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Đơn vị:</dt>
                                    <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $product->unit ?? 'Chưa cập nhật' }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-3">Thời gian</h4>
                            <dl class="space-y-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Ngày tạo:</dt>
                                    <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $product->created_at->format('d/m/Y H:i:s') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Cập nhật lần cuối:</dt>
                                    <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $product->updated_at->format('d/m/Y H:i:s') }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Mô tả -->
                    @if($product->description)
                    <div class="mb-8">
                        <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-3">Mô tả sản phẩm</h4>
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $product->description }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Tồn kho theo kho -->
                    @if($product->inventory->count() > 0)
                    <div class="mb-8">
                        <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-3">Tồn kho theo kho hàng</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Kho hàng
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Địa điểm
                                        </th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Số lượng
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($product->inventory as $inventory)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $inventory->warehouse->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $inventory->warehouse->location ?? 'Chưa cập nhật' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                {{ $inventory->quantity > 0 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                                {{ number_format($inventory->quantity) }} {{ $product->unit ?? '' }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <td colspan="2" class="px-6 py-3 text-left text-sm font-medium text-gray-900 dark:text-gray-100">
                                            Tổng tồn kho:
                                        </td>
                                        <td class="px-6 py-3 text-right text-sm font-bold">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                {{ number_format($product->inventory->sum('quantity')) }} {{ $product->unit ?? '' }}
                                            </span>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Chưa có tồn kho</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Sản phẩm này chưa có trong kho hàng nào.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
