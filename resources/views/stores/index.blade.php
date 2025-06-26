<x-app-layout>
    <x-slot name="header">
        <div class="page-header-standard">
            <div class="page-header-content">
                <div class="page-title-section">
                    <h1 class="page-title-main text-gray-900">
                        <div class="page-title-icon simple-bg">
                            <i class="fas fa-store text-blue-600 text-lg"></i>
                        </div>
                        {{ __('Quản lý Cửa hàng') }}
                    </h1>
                    <p class="page-subtitle">Quản lý danh sách cửa hàng và thông tin chi tiết</p>
                </div>
                <div class="page-actions">
                    <a href="{{ route('stores.create') }}" class="btn-primary-standard">
                        <i class="fas fa-plus mr-2"></i>
                        Thêm Cửa hàng Mới
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="page-container">
        <div class="page-content">
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
            @endif

            @if($stores->count() > 0)
                <!-- Stores Table -->
                <div class="table-container">
                    <div class="table-wrapper">
                        <!-- Stores Table -->
                        <x-table 
                            :headers="['Tên Cửa hàng', 'Địa chỉ', 'Số điện thoại', 'Quản lý', 'Trạng thái', 'Hành động']"
                            :mobileCards="true"
                            class="min-w-full">
                            @foreach($stores as $store)
                                <tr class="hover:bg-blue-50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap" data-label="Tên Cửa hàng">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $store->name }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap" data-label="Địa chỉ">
                                        <div class="text-sm text-gray-900">
                                            {{ $store->location ?? 'Chưa có địa chỉ' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap" data-label="Số điện thoại">
                                        <div class="text-sm text-gray-900">
                                            {{ $store->phone ?? 'Chưa có SĐT' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap" data-label="Quản lý">
                                        <div class="text-sm text-gray-900">
                                            {{ $store->manager ?? 'Chưa có quản lý' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap" data-label="Trạng thái">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $store->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $store->status ? 'Hoạt động' : 'Ngừng hoạt động' }}
                                        </span>
                                    </td>
                                    <td class="table-actions" data-label="Hành động">
                                        <div class="action-buttons">
                                            <a href="{{ route('stores.show', $store) }}" 
                                               class="action-btn action-btn-view"
                                               title="Xem chi tiết">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('stores.edit', $store) }}" 
                                               class="action-btn action-btn-edit"
                                               title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('stores.destroy', $store) }}" method="POST" 
                                                  class="inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa cửa hàng này?')">
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
                        </x-table>
                        
                        <!-- Pagination -->
                        @if($stores->hasPages())
                            <div class="mt-6">
                                {{ $stores->links() }}
                            </div>
                        @endif
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-12">
                            <div class="flex flex-col items-center justify-center space-y-4">
                                <svg class="w-16 h-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                <div class="text-gray-500 dark:text-gray-400">
                                    <p class="text-lg font-medium">Chưa có cửa hàng nào</p>
                                    <p class="text-sm">Hãy thêm cửa hàng đầu tiên của bạn</p>
                                </div>
                                <a href="{{ route('stores.create') }}" 
                                   class="btn-add-new inline-flex items-center px-4 py-2 text-sm font-medium rounded-md transition-all duration-200 shadow-md hover:shadow-lg touch-target">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Thêm Cửa hàng Mới
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>        </div>
    </div>
</x-app-layout>
