@extends('layouts.app')

@section('content')
<div class="py-4 sm:py-6">
    <div class="container-70">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-gray-200">Tạo đơn hàng thử nghiệm</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2">Tạo các đơn hàng mẫu để kiểm tra tính năng hệ thống</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('notifications.index') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg touch-target">
                    <i class="fas fa-bell mr-2"></i>Xem thông báo
                </a>
            </div>
        </div>        <!-- Success/Error Messages -->
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            {{ session('error') }}
        </div>
        @endif

        <!-- Simple Test Order Creation -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Create Test Return Order -->
            <div class="bg-white rounded-lg shadow-lg">
                <div class="bg-gradient-to-r from-red-500 to-red-600 text-white p-6 rounded-t-lg">
                    <h3 class="text-xl font-semibold flex items-center">
                        <i class="fas fa-undo mr-3"></i>
                        Tạo đơn trả hàng thử nghiệm
                    </h3>
                    <p class="mt-2 text-red-100">Tạo đơn trả hàng mẫu để kiểm tra tính năng</p>
                </div>

                <form action="{{ route('admin.auto-generation.test-return') }}" method="POST" class="p-6">
                    @csrf
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Số lượng đơn trả hàng
                            </label>
                            <input type="number" name="count" value="1" min="1" max="10"
                                   class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500">
                            <p class="text-xs text-gray-500 mt-1">Tạo từ 1-10 đơn trả hàng thử nghiệm</p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" 
                                class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-3 px-4 rounded-lg transition duration-200">
                            <i class="fas fa-plus mr-2"></i>
                            Tạo đơn trả hàng thử nghiệm
                        </button>
                    </div>
                </form>
            </div>

            <!-- Create Test Shipment Order -->
            <div class="bg-white rounded-lg shadow-lg">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6 rounded-t-lg">
                    <h3 class="text-xl font-semibold flex items-center">
                        <i class="fas fa-truck mr-3"></i>
                        Tạo đơn gửi hàng thử nghiệm
                    </h3>
                    <p class="mt-2 text-blue-100">Tạo đơn gửi hàng mẫu để kiểm tra tính năng</p>
                </div>

                <form action="{{ route('admin.auto-generation.test-shipment') }}" method="POST" class="p-6">
                    @csrf
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Số lượng đơn gửi hàng
                            </label>
                            <input type="number" name="count" value="1" min="1" max="10"
                                   class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <p class="text-xs text-gray-500 mt-1">Tạo từ 1-10 đơn gửi hàng thử nghiệm</p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" 
                                class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 px-4 rounded-lg transition duration-200">
                            <i class="fas fa-plus mr-2"></i>
                            Tạo đơn gửi hàng thử nghiệm
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Info Section -->
        <div class="mt-8 bg-white rounded-lg shadow-lg">
            <div class="bg-gradient-to-r from-green-500 to-green-600 text-white p-6 rounded-t-lg">
                <h2 class="text-xl font-semibold flex items-center">
                    <i class="fas fa-info-circle mr-3"></i>
                    Thông tin về đơn hàng thử nghiệm
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-3">Đơn trả hàng thử nghiệm</h3>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                Tự động chọn cửa hàng ngẫu nhiên
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                Tạo 1-3 sản phẩm ngẫu nhiên mỗi đơn
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                Số lượng từ 1-10 mỗi sản phẩm
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-3">Đơn gửi hàng thử nghiệm</h3>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                Tự động chọn cửa hàng ngẫu nhiên
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                Tạo 1-5 sản phẩm ngẫu nhiên mỗi đơn
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                Số lượng từ 5-50 mỗi sản phẩm
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
