<x-app-layout>
    <x-slot name="header">
        <div class="page-header-standard">
            <div class="page-header-content">
                <div class="page-title-section">
                    <h1 class="page-title-main text-gray-900">
                        <div class="page-title-icon simple-bg">
                            <i class="fas fa-truck text-orange-600 text-lg"></i>
                        </div>
                        {{ __('Quản lý nhà cung cấp') }}
                    </h1>
                    <p class="page-subtitle">Quản lý thông tin các nhà cung cấp hàng hóa</p>
                </div>
                <div class="page-actions">
                    <a href="{{ route('suppliers.create') }}" class="btn-primary-standard">
                        <i class="fas fa-plus mr-2"></i>
                        Thêm nhà cung cấp
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="page-container">
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
            @endif

            <!-- Suppliers Table -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <div class="section-divider">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                            <i class="fas fa-truck mr-2 text-green-600"></i>
                            Danh sách nhà cung cấp
                        </h3>
                    </div>

                    <div class="table-wrapper">
                        <!-- Suppliers Table -->
                        <x-table 
                            :headers="['Nhà cung cấp', 'Thông tin liên hệ', 'Trạng thái', 'Ngày tạo', 'Thao tác']"
                        :mobileCards="true"
                        class="min-w-full">
                        @forelse($suppliers as $supplier)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap" data-label="Nhà cung cấp">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $supplier->name }}
                                        </div>
                                        @if($supplier->contact_person)
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                Liên hệ: {{ $supplier->contact_person }}
                                            </div>
                                        @endif
                                        @if($supplier->description)
                                            <div class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                                {{ Str::limit($supplier->description, 80) }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap" data-label="Thông tin liên hệ">
                                    <div class="text-sm">
                                        @if($supplier->email)
                                            <div class="text-gray-900 dark:text-gray-100">
                                                <a href="mailto:{{ $supplier->email }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                                    {{ $supplier->email }}
                                                </a>
                                            </div>
                                        @endif
                                        @if($supplier->phone)
                                            <div class="text-gray-500 dark:text-gray-400">
                                                <a href="tel:{{ $supplier->phone }}" class="hover:text-gray-700 dark:hover:text-gray-300">
                                                    {{ $supplier->phone }}
                                                </a>
                            </div>
                                        @endif
                                        @if($supplier->tax_number)
                                            <div class="text-xs text-gray-400 dark:text-gray-500">
                                                MST: {{ $supplier->tax_number }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap" data-label="Trạng thái">
                                    @if($supplier->status === 'active')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                            Hoạt động
                                        </span>
                                    @else
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100">
                                            Không hoạt động
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm date-text" data-label="Ngày tạo">
                                    {{ $supplier->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="table-actions" data-label="Thao tác">
                                    <div class="action-buttons">
                                        <a href="{{ route('suppliers.show', $supplier) }}" 
                                           class="action-btn action-btn-view"
                                           title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('suppliers.edit', $supplier) }}" 
                                           class="action-btn action-btn-edit"
                                           title="Chỉnh sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="action-btn action-btn-delete"
                                                    title="Xóa"
                                                    onclick="return confirm('Bạn có chắc chắn muốn xóa nhà cung cấp này?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center space-y-4">
                                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                        <div class="text-gray-500 dark:text-gray-400">
                                            <p class="text-lg font-medium">Chưa có nhà cung cấp nào</p>
                                            <p class="text-sm">Bắt đầu bằng cách thêm nhà cung cấp đầu tiên</p>
                                        </div>
                                        <a href="{{ route('suppliers.create') }}" 
                                           class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white text-sm font-medium rounded-md transition-all duration-200 shadow-md hover:shadow-lg touch-target">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                            Thêm nhà cung cấp
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </x-table>

                    <!-- Pagination -->
                    @if($suppliers->hasPages())
                        <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                            {{ $suppliers->links() }}
                        </div>
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>