<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Quản lý hóa đơn nhập kho') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">Danh sách hóa đơn nhập kho</h3>
                        <a href="{{ route('purchase-orders.create') }}" 
                           class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-md transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Tạo hóa đơn mới
                        </a>
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
                    @endif                    <!-- Search and Filter Section with Real-time Search -->
                    <div class="mb-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <!-- Warehouse Search -->
                            <div class="relative">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kho hàng</label>
                                <div class="relative">
                                    <input type="text" 
                                           id="warehouseSearch" 
                                           placeholder="Tìm kiếm kho hàng..."
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md leading-5 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                    <div id="warehouseDropdown" class="absolute z-[9999] mt-1 w-full bg-white dark:bg-gray-700 shadow-2xl max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none hidden" style="z-index: 9999 !important;">
                                        <!-- Dynamic content -->
                                    </div>
                                </div>
                                <input type="hidden" name="warehouse_id" id="selectedWarehouse" value="{{ request('warehouse_id') }}">
                            </div>

                            <!-- Status Filter -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Trạng thái</label>
                                <select name="status" id="statusFilter" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md leading-5 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                    <option value="">Tất cả trạng thái</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                                </select>
                            </div>

                            <!-- Search Input -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tìm kiếm</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </div>
                                    <input type="text" 
                                           name="search" 
                                           id="globalSearch"
                                           class="w-full pl-10 pr-10 py-2 border border-gray-300 dark:border-gray-600 rounded-md leading-5 bg-white dark:bg-gray-700 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-gray-100 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                           placeholder="Tìm theo số hóa đơn, nhà cung cấp..." 
                                           value="{{ request('search') }}">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <div id="searchLoader" class="hidden">
                                            <svg class="animate-spin h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </div>
                                        <button type="button" id="clearSearch" class="hidden text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Search Results Summary -->
                        <div id="searchResults" class="hidden">
                            <div class="bg-blue-50 dark:bg-blue-900/50 border border-blue-200 dark:border-blue-700 rounded-md p-3">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span id="searchResultsText" class="text-sm text-blue-700 dark:text-blue-300"></span>
                                </div>
                            </div>
                        </div>
                    </div>                    <!-- Enhanced Purchase Orders Table -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg border border-gray-200 dark:border-gray-700">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700" id="purchaseOrdersTable">
                                <thead class="bg-gradient-to-r from-purple-50 to-blue-50 dark:from-gray-700 dark:to-gray-800">
                                    <tr>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                            <div class="flex items-center space-x-1">
                                                <span>Số hóa đơn</span>
                                                <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                            </div>
                                        </th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                            <div class="flex items-center space-x-1">
                                                <span>Kho hàng</span>
                                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                </svg>
                                            </div>
                                        </th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                            <div class="flex items-center space-x-1">
                                                <span>Nhà cung cấp</span>
                                                <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                </svg>
                                            </div>
                                        </th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                            <div class="flex items-center space-x-1">
                                                <span>Tổng tiền</span>
                                                <svg class="w-4 h-4 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                                </svg>
                                            </div>
                                        </th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                            <div class="flex items-center space-x-1">
                                                <span>Trạng thái</span>
                                                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                        </th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                            <div class="flex items-center space-x-1">
                                                <span>Ngày tạo</span>
                                                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        </th>
                                        <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                            <div class="flex items-center justify-center space-x-1">
                                                <span>Thao tác</span>
                                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                                </svg>
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700" id="tableBody">
                                    @forelse($purchaseOrders as $order)
                                        <tr class="hover:bg-gradient-to-r hover:from-purple-50 hover:to-blue-50 dark:hover:from-gray-700 dark:hover:to-gray-600 transition-all duration-200 transform hover:scale-[1.01]">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-8 w-8">
                                                        <div class="h-8 w-8 rounded-full bg-gradient-to-r from-purple-400 to-blue-500 flex items-center justify-center">
                                                            <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <div class="ml-3">
                                                        <a href="{{ route('purchase-orders.show', $order) }}" 
                                                           class="text-purple-600 hover:text-purple-900 dark:text-purple-400 dark:hover:text-purple-300 font-semibold text-sm transition-colors duration-200">
                                                            {{ $order->invoice_number }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-6 w-6">
                                                        <div class="h-6 w-6 rounded bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                                            <svg class="h-3 w-3 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <span class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $order->warehouse->name }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm">
                                                    <div class="font-semibold text-gray-900 dark:text-gray-100">{{ $order->supplier_name }}</div>
                                                    @if($order->supplier_phone)
                                                        <div class="text-gray-500 dark:text-gray-400 text-xs">{{ $order->supplier_phone }}</div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-bold text-green-600 dark:text-green-400">
                                                    {{ number_format($order->total_amount, 0, ',', '.') }} VNĐ
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($order->status == 'pending')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gradient-to-r from-yellow-100 to-yellow-200 text-yellow-800 dark:from-yellow-800 dark:to-yellow-900 dark:text-yellow-100 border border-yellow-300 dark:border-yellow-600">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        Chờ xử lý
                                                    </span>
                                                @elseif($order->status == 'confirmed')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gradient-to-r from-green-100 to-green-200 text-green-800 dark:from-green-800 dark:to-green-900 dark:text-green-100 border border-green-300 dark:border-green-600">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        Đã xác nhận
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gradient-to-r from-blue-100 to-blue-200 text-blue-800 dark:from-blue-800 dark:to-blue-900 dark:text-blue-100 border border-blue-300 dark:border-blue-600">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                        Hoàn thành
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                    <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <div class="flex items-center justify-center space-x-2">
                                                    <a href="{{ route('purchase-orders.show', $order) }}" 
                                                       class="inline-flex items-center px-2 py-1 border border-blue-300 rounded-md text-blue-600 hover:text-blue-900 hover:bg-blue-50 dark:text-blue-400 dark:hover:text-blue-300 dark:border-blue-600 dark:hover:bg-blue-900 transition-all duration-200">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                        </svg>
                                                    </a>
                                                    @if($order->isPending())
                                                        <a href="{{ route('purchase-orders.edit', $order) }}" 
                                                           class="inline-flex items-center px-2 py-1 border border-yellow-300 rounded-md text-yellow-600 hover:text-yellow-900 hover:bg-yellow-50 dark:text-yellow-400 dark:hover:text-yellow-300 dark:border-yellow-600 dark:hover:bg-yellow-900 transition-all duration-200">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                            </svg>
                                                        </a>
                                                        <form action="{{ route('purchase-orders.destroy', $order) }}" 
                                                              method="POST" class="inline"
                                                              onsubmit="return confirm('Bạn có chắc chắn muốn xóa hóa đơn này?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="inline-flex items-center px-2 py-1 border border-red-300 rounded-md text-red-600 hover:text-red-900 hover:bg-red-50 dark:text-red-400 dark:hover:text-red-300 dark:border-red-600 dark:hover:bg-red-900 transition-all duration-200">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="px-6 py-16 text-center">
                                                <div class="flex flex-col items-center justify-center space-y-4">
                                                    <div class="w-16 h-16 bg-gradient-to-br from-purple-100 to-blue-100 dark:from-gray-700 dark:to-gray-600 rounded-full flex items-center justify-center">
                                                        <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Không có hóa đơn nào</h3>
                                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Hãy tạo hóa đơn nhập kho đầu tiên của bạn.</p>
                                                    </div>
                                                    <a href="{{ route('purchase-orders.create') }}" 
                                                       class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white text-sm font-medium rounded-lg shadow-lg transform transition-all duration-200 hover:scale-105">
                                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                        </svg>
                                                        Tạo hóa đơn mới
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Phân trang -->
                    <div class="mt-6">
                        {{ $purchaseOrders->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
let searchTimeout;
let currentFilters = {
    invoice_number: '',
    warehouse: '',
    supplier: '',
    status: ''
};

document.addEventListener('DOMContentLoaded', function() {
    // Initialize search functionality
    initializeSearch();
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.relative')) {
            document.querySelectorAll('[id$="_dropdown"]').forEach(dropdown => {
                dropdown.classList.add('hidden');
            });
        }
    });
});

function initializeSearch() {
    // Invoice number search
    const invoiceInput = document.getElementById('invoice_search');
    if (invoiceInput) {
        invoiceInput.addEventListener('input', function() {
            currentFilters.invoice_number = this.value.trim();
            debounceSearch();
        });
    }
    
    // Warehouse search
    const warehouseInput = document.getElementById('warehouse_search');
    if (warehouseInput) {
        warehouseInput.addEventListener('input', function() {
            const query = this.value.trim();
            if (query.length >= 2) {
                searchWarehouses(query);
            } else {
                document.getElementById('warehouse_dropdown').classList.add('hidden');
            }
        });
    }
    
    // Supplier search
    const supplierInput = document.getElementById('supplier_search');
    if (supplierInput) {
        supplierInput.addEventListener('input', function() {
            const query = this.value.trim();
            if (query.length >= 2) {
                searchSuppliers(query);
            } else {
                document.getElementById('supplier_dropdown').classList.add('hidden');
            }
        });
    }
    
    // Status filter
    const statusSelect = document.getElementById('status_filter');
    if (statusSelect) {
        statusSelect.addEventListener('change', function() {
            currentFilters.status = this.value;
            performSearch();
        });
    }
}

function debounceSearch() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        performSearch();
    }, 500);
}

async function performSearch() {
    const loadingIndicator = document.getElementById('loading');
    const tableBody = document.getElementById('tableBody');
    const searchSummary = document.getElementById('searchSummary');
    
    if (loadingIndicator) loadingIndicator.classList.remove('hidden');
    
    try {
        const params = new URLSearchParams();
        Object.keys(currentFilters).forEach(key => {
            if (currentFilters[key]) {
                params.append(key, currentFilters[key]);
            }
        });
        
        const response = await fetch(`{{ route('purchase-orders.index') }}?${params.toString()}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        if (!response.ok) throw new Error('Network response was not ok');
        
        const data = await response.json();
        updateTable(data.data);
        updateSearchSummary(data.total, data.filters);
        
    } catch (error) {
        console.error('Search error:', error);
        if (tableBody) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-red-500">
                        <div class="flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Lỗi khi tìm kiếm. Vui lòng thử lại.</span>
                        </div>
                    </td>
                </tr>
            `;
        }
    } finally {
        if (loadingIndicator) loadingIndicator.classList.add('hidden');
    }
}

async function searchWarehouses(query) {
    const dropdown = document.getElementById('warehouse_dropdown');
    const loading = document.getElementById('warehouse_loading');
    const results = document.getElementById('warehouse_results');
    
    if (!dropdown || !loading || !results) return;
    
    loading.classList.remove('hidden');
    results.innerHTML = '';
    dropdown.classList.remove('hidden');
    
    try {
        const response = await fetch(`/api/warehouses/search?q=${encodeURIComponent(query)}`);
        const data = await response.json();
        
        loading.classList.add('hidden');
        
        if (data.length === 0) {
            results.innerHTML = '<div class="px-4 py-2 text-gray-500 dark:text-gray-400 text-sm">Không tìm thấy kho hàng nào</div>';
        } else {
            results.innerHTML = data.map(warehouse => `
                <div class="px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer border-b border-gray-200 dark:border-gray-600 last:border-b-0" 
                     onclick="selectWarehouse('${warehouse.name}')">
                    <div class="font-medium text-gray-900 dark:text-gray-100">${warehouse.name}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">${warehouse.address || 'Không có địa chỉ'}</div>
                </div>
            `).join('');
        }
    } catch (error) {
        loading.classList.add('hidden');
        results.innerHTML = '<div class="px-4 py-2 text-red-500 text-sm">Lỗi khi tìm kiếm</div>';
    }
}

async function searchSuppliers(query) {
    const dropdown = document.getElementById('supplier_dropdown');
    const loading = document.getElementById('supplier_loading');
    const results = document.getElementById('supplier_results');
    
    if (!dropdown || !loading || !results) return;
    
    loading.classList.remove('hidden');
    results.innerHTML = '';
    dropdown.classList.remove('hidden');
    
    try {
        const response = await fetch(`/api/suppliers/search?q=${encodeURIComponent(query)}`);
        const data = await response.json();
        
        loading.classList.add('hidden');
        
        if (data.length === 0) {
            results.innerHTML = '<div class="px-4 py-2 text-gray-500 dark:text-gray-400 text-sm">Không tìm thấy nhà cung cấp nào</div>';
        } else {
            results.innerHTML = data.map(supplier => `
                <div class="px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer border-b border-gray-200 dark:border-gray-600 last:border-b-0" 
                     onclick="selectSupplier('${supplier.name}')">
                    <div class="font-medium text-gray-900 dark:text-gray-100">${supplier.name}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">${supplier.phone || 'Không có SĐT'}</div>
                </div>
            `).join('');
        }
    } catch (error) {
        loading.classList.add('hidden');
        results.innerHTML = '<div class="px-4 py-2 text-red-500 text-sm">Lỗi khi tìm kiếm</div>';
    }
}

function selectWarehouse(name) {
    document.getElementById('warehouse_search').value = name;
    currentFilters.warehouse = name;
    document.getElementById('warehouse_dropdown').classList.add('hidden');
    performSearch();
}

function selectSupplier(name) {
    document.getElementById('supplier_search').value = name;
    currentFilters.supplier = name;
    document.getElementById('supplier_dropdown').classList.add('hidden');
    performSearch();
}

function updateTable(orders) {
    const tableBody = document.getElementById('tableBody');
    if (!tableBody) return;
    
    if (orders.length === 0) {
        tableBody.innerHTML = `
            <tr>
                <td colspan="7" class="px-6 py-16 text-center">
                    <div class="flex flex-col items-center justify-center space-y-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-100 to-blue-100 dark:from-gray-700 dark:to-gray-600 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Không tìm thấy kết quả</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Hãy thử điều chỉnh từ khóa tìm kiếm.</p>
                        </div>
                    </div>
                </td>
            </tr>
        `;
        return;
    }
    
    tableBody.innerHTML = orders.map(order => {
        const statusBadge = getStatusBadge(order.status);
        const date = new Date(order.created_at).toLocaleDateString('vi-VN');
        const total = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(order.total_amount || 0);
        
        return `
            <tr class="hover:bg-gradient-to-r hover:from-purple-50 hover:to-blue-50 dark:hover:from-gray-700 dark:hover:to-gray-600 transition-all duration-200 transform hover:scale-[1.01]">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-8 w-8">
                            <div class="h-8 w-8 rounded-full bg-gradient-to-r from-purple-400 to-blue-500 flex items-center justify-center">
                                <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <a href="/purchase-orders/${order.id}" 
                               class="text-purple-600 hover:text-purple-900 dark:text-purple-400 dark:hover:text-purple-300 font-semibold text-sm transition-colors duration-200">
                                ${order.invoice_number}
                            </a>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-6 w-6">
                            <div class="h-6 w-6 rounded bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                <svg class="h-3 w-3 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                        </div>
                        <span class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">${order.warehouse?.name || 'N/A'}</span>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-6 w-6">
                            <div class="h-6 w-6 rounded bg-green-100 dark:bg-green-900 flex items-center justify-center">
                                <svg class="h-3 w-3 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                        </div>
                        <span class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">${order.supplier_name}</span>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">${statusBadge}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-sm font-bold text-purple-600 dark:text-purple-400">${total}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                    <div class="flex items-center">
                        <svg class="h-4 w-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        ${date}
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                    <div class="flex items-center justify-center space-x-2">
                        <a href="/purchase-orders/${order.id}" 
                           class="bg-gradient-to-r from-blue-500 to-cyan-600 hover:from-blue-600 hover:to-cyan-700 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition-all duration-200 transform hover:scale-105 shadow-sm">
                            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            Xem
                        </a>
                        <a href="/purchase-orders/${order.id}/edit" 
                           class="bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition-all duration-200 transform hover:scale-105 shadow-sm">
                            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Sửa
                        </a>
                    </div>
                </td>
            </tr>
        `;
    }).join('');
}

function getStatusBadge(status) {
    const statusConfig = {
        'pending': { 
            class: 'bg-gradient-to-r from-yellow-400 to-amber-500 text-white', 
            text: 'Chờ xử lý',
            icon: 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'
        },
        'processing': { 
            class: 'bg-gradient-to-r from-blue-500 to-indigo-600 text-white', 
            text: 'Đang xử lý',
            icon: 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15'
        },
        'approved': { 
            class: 'bg-gradient-to-r from-green-500 to-emerald-600 text-white', 
            text: 'Đã duyệt',
            icon: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'
        },
        'cancelled': { 
            class: 'bg-gradient-to-r from-red-500 to-rose-600 text-white', 
            text: 'Đã hủy',
            icon: 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'
        }
    };
    
    const config = statusConfig[status] || statusConfig['pending'];
    
    return `
        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold ${config.class} shadow-sm">
            <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${config.icon}"></path>
            </svg>
            ${config.text}
        </span>
    `;
}

function updateSearchSummary(total, filters) {
    const summaryElement = document.getElementById('searchSummary');
    if (!summaryElement) return;
    
    const activeFilters = Object.values(filters || currentFilters).filter(v => v && v.trim() !== '').length;
    
    if (activeFilters > 0) {
        summaryElement.innerHTML = `
            <div class="flex items-center justify-between bg-gradient-to-r from-purple-50 to-blue-50 dark:from-gray-700 dark:to-gray-600 px-4 py-3 rounded-lg border border-purple-200 dark:border-gray-600">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <span class="text-sm font-medium text-purple-900 dark:text-purple-100">
                        Tìm thấy <strong>${total}</strong> kết quả với <strong>${activeFilters}</strong> bộ lọc
                    </span>
                </div>
                <button onclick="clearAllFilters()" 
                        class="text-sm text-purple-600 hover:text-purple-800 dark:text-purple-400 dark:hover:text-purple-300 font-medium transition-colors">
                    Xóa bộ lọc
                </button>
            </div>
        `;
        summaryElement.classList.remove('hidden');
    } else {
        summaryElement.classList.add('hidden');
    }
}

function clearAllFilters() {
    // Reset all filter values
    currentFilters = {
        invoice_number: '',
        warehouse: '',
        supplier: '',
        status: ''
    };
    
    // Clear input fields
    const inputs = ['invoice_search', 'warehouse_search', 'supplier_search', 'status_filter'];
    inputs.forEach(id => {
        const element = document.getElementById(id);
        if
