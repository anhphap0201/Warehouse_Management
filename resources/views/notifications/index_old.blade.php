@extends('layouts.app')

@section('content')
<div class="py-4 sm:py-6">
    <div class="container-70">        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-gray-200">Quản lý thông báo</h1>
            <div class="flex gap-3">
                <button id="markAllRead" class="btn-add-new inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg touch-target">
                    <i class="fas fa-check-double mr-2"></i>Đánh dấu tất cả đã đọc
                </button>
            </div>
        </div>
        
        <!-- Instructions -->
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-yellow-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        Thông báo sẽ chỉ được đánh dấu đã đọc khi bạn nhấp vào để xem chi tiết. Các thông báo chưa đọc được đánh dấu bằng <span class="font-semibold text-yellow-800 bg-transparent">nền xanh</span> và <span class="font-semibold text-yellow-800 bg-transparent">biểu tượng "Chưa đọc"</span>.
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Filter tabs -->
        <div class="mb-6 border-b border-gray-200 dark:border-gray-700">
            <nav class="-mb-px flex flex-wrap gap-2 sm:gap-0 sm:space-x-8">
                <a href="{{ route('notifications.index') }}" 
                   class="border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm touch-target {{ request('status') === null ? 'border-indigo-500 text-indigo-600 dark:border-indigo-400 dark:text-indigo-400' : '' }}">
                    Tất cả
                </a>
                <a href="{{ route('notifications.index', ['status' => 'pending']) }}" 
                   class="border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm touch-target {{ request('status') === 'pending' ? 'border-indigo-500 text-indigo-600 dark:border-indigo-400 dark:text-indigo-400' : '' }}">
                    Chờ xử lý
                    @if($notifications->where('status', 'pending')->count() > 0)
                        <span class="bg-transparent border border-red-200 text-red-600 text-xs font-semibold ml-2 px-2.5 py-0.5 rounded-full">
                            {{ $notifications->where('status', 'pending')->count() }}
                        </span>
                    @endif
                </a>
                <a href="{{ route('notifications.index', ['status' => 'approved']) }}" 
                   class="border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm touch-target {{ request('status') === 'approved' ? 'border-indigo-500 text-indigo-600 dark:border-indigo-400 dark:text-indigo-400' : '' }}">
                    Đã phê duyệt
                </a>
                <a href="{{ route('notifications.index', ['status' => 'rejected']) }}" 
                   class="border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm touch-target {{ request('status') === 'rejected' ? 'border-indigo-500 text-indigo-600 dark:border-indigo-400 dark:text-indigo-400' : '' }}">
                    Đã từ chối
                </a>
            </nav>
        </div>

    <div class="bg-transparent shadow-lg rounded-lg overflow-hidden border border-gray-200">        @forelse($notifications as $notification)
            <div class="notification-item border-b border-gray-200 {{ !$notification->read_at ? 'bg-blue-50' : '' }}">
                <div class="p-6 hover:bg-gray-50 cursor-pointer relative" onclick="window.location='{{ route('notifications.show', $notification) }}'">
                    @if(!$notification->read_at)
                        <div class="absolute top-0 right-0 mt-4 mr-4">
                            <span class="flex h-3 w-3">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                            </span>
                        </div>
                    @endif
                    <div class="flex items-start justify-between">
                        <div class="flex items-start space-x-4 flex-1">
                            <!-- Notification Icon -->
                            <div class="flex-shrink-0">
                                @if($notification->type === 'receive_request')
                                    <div class="w-10 h-10 {{ !$notification->read_at ? 'bg-green-200' : 'bg-green-100' }} rounded-full flex items-center justify-center">
                                        <i class="fas fa-arrow-down text-green-600"></i>
                                    </div>
                                @else
                                    <div class="w-10 h-10 {{ !$notification->read_at ? 'bg-orange-200' : 'bg-orange-100' }} rounded-full flex items-center justify-center">
                                        <i class="fas fa-arrow-up text-orange-600"></i>
                                    </div>
                                @endif
                            </div>

                            <!-- Notification Content -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <h3 class="text-lg font-semibold {{ !$notification->read_at ? 'text-blue-900' : 'text-gray-900' }}">{{ $notification->title }}</h3>
                                    @if(!$notification->read_at)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-bell mr-1"></i> Chưa đọc
                                        </span>
                                    @endif
                                </div>
                                
                                <p class="text-gray-600 mb-2">{{ Str::limit($notification->message, 100) }}</p>
                                  <div class="flex items-center text-sm text-gray-500 space-x-4">
                                    <span>
                                        <i class="fas fa-store mr-1"></i>
                                        {{ $notification->store->name }}
                                    </span>
                                    @if($notification->warehouse)
                                        <span>
                                            <i class="fas fa-warehouse mr-1"></i>
                                            {{ $notification->warehouse->name }}
                                        </span>
                                    @endif
                                    <span>
                                        <i class="fas fa-calendar mr-1"></i>
                                        {{ $notification->created_at->format('d/m/Y H:i') }}
                                    </span>
                                </div>
                            </div>
                        </div>                        <!-- Status Badge and Action Buttons -->
                        <div class="flex-shrink-0 ml-4">
                            @if($notification->status === 'pending')
                                <div class="flex items-center space-x-2">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-1"></i>
                                        Chờ xử lý
                                    </span>
                                    <div class="flex space-x-1">
                                        <button type="button" onclick="openApprovalModal({{ $notification->id }})" 
                                                class="bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded text-sm transition duration-200">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button type="button" onclick="openRejectionModal({{ $notification->id }})"
                                                class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-sm transition duration-200">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            @elseif($notification->status === 'approved')
                                <div class="flex items-center space-x-2">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-1"></i>
                                        Đã phê duyệt
                                    </span>
                                    @if($notification->warehouse)
                                        <span class="text-xs text-blue-600 bg-transparent px-2 py-1 rounded border border-blue-200">
                                            {{ $notification->warehouse->name }}
                                        </span>
                                    @endif
                                </div>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-times mr-1"></i>
                                    Đã từ chối
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="p-8 text-center">
                <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-bell text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Không có thông báo</h3>
                <p class="text-gray-500">Chưa có thông báo nào được tạo.</p>
            </div>
        @endforelse
    </div>    <!-- Pagination -->
    @if($notifications->hasPages())
        <div class="mt-6 flex justify-center">
            <div class="bg-transparent px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                <div class="flex-1 flex justify-between sm:hidden">
                    @if($notifications->onFirstPage())
                        <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-transparent border border-gray-300 cursor-default leading-5 rounded-md">
                            Trước
                        </span>
                    @else
                        <a href="{{ $notifications->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-transparent border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                            Trước
                        </a>
                    @endif

                    @if($notifications->hasMorePages())
                        <a href="{{ $notifications->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-transparent border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                            Sau
                        </a>
                    @else
                        <span class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-500 bg-transparent border border-gray-300 cursor-default leading-5 rounded-md">
                            Sau
                        </span>
                    @endif
                </div>

                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700 leading-5">
                            Hiển thị
                            <span class="font-medium">{{ $notifications->firstItem() }}</span>
                            đến
                            <span class="font-medium">{{ $notifications->lastItem() }}</span>
                            của
                            <span class="font-medium">{{ $notifications->total() }}</span>
                            kết quả
                        </p>
                    </div>

                    <div>
                        <span class="relative z-0 inline-flex shadow-sm rounded-md">
                            @if($notifications->onFirstPage())
                                <span aria-disabled="true" aria-label="Trước">
                                    <span class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-transparent border border-gray-300 cursor-default rounded-l-md leading-5" aria-hidden="true">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                </span>
                            @else
                                <a href="{{ $notifications->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-transparent border border-gray-300 rounded-l-md leading-5 hover:text-gray-400 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150" aria-label="Trước">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            @endif

                            @foreach ($notifications->getUrlRange(1, $notifications->lastPage()) as $page => $url)
                                @if ($page == $notifications->currentPage())
                                    <span aria-current="page">
                                        <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-white bg-blue-600 border border-blue-600 cursor-default leading-5">{{ $page }}</span>
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-transparent border border-gray-300 leading-5 hover:text-gray-500 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150" aria-label="Trang {{ $page }}">{{ $page }}</a>
                                @endif
                            @endforeach

                            @if($notifications->hasMorePages())
                                <a href="{{ $notifications->nextPageUrl() }}" rel="next" class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-500 bg-transparent border border-gray-300 rounded-r-md leading-5 hover:text-gray-400 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150" aria-label="Sau">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            @else
                                <span aria-disabled="true" aria-label="Sau">
                                    <span class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-500 bg-transparent border border-gray-300 cursor-default rounded-r-md leading-5" aria-hidden="true">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </span>
                                </span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Approval Modal -->
<div id="approvalModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" onclick="closeApprovalModal()"></div>
        
        <div class="inline-block align-bottom bg-transparent rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-200">
            <form id="approvalForm" method="POST" action="">
                @csrf
                <div class="bg-transparent px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-check text-green-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                                Phê duyệt yêu cầu
                            </h3>
                            
                            <div class="mb-4">
                                <label for="warehouse_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Chỉ định kho <span class="text-red-500">*</span>
                                </label>
                                <select name="warehouse_id" id="warehouse_id" required 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Chọn kho --</option>
                                    @foreach($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }} - {{ $warehouse->location }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="mb-4">
                                <label for="admin_response" class="block text-sm font-medium text-gray-700 mb-2">
                                    Phản hồi từ quản lý
                                </label>
                                <textarea name="admin_response" id="admin_response" rows="3" 
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                          placeholder="Ghi chú về việc phê duyệt..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" 
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Phê duyệt
                    </button>
                    <button type="button" onclick="closeApprovalModal()" 
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-transparent text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Hủy
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Rejection Modal -->
<div id="rejectionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" onclick="closeRejectionModal()"></div>
        
        <div class="inline-block align-bottom bg-transparent rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-200">
            <form id="rejectionForm" method="POST" action="">
                @csrf
                <div class="bg-transparent px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-times text-red-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                                Từ chối yêu cầu
                            </h3>
                            
                            <div class="mb-4">
                                <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">
                                    Lý do từ chối <span class="text-red-500">*</span>
                                </label>
                                <textarea name="rejection_reason" id="rejection_reason" rows="4" required
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                          placeholder="Nhập lý do từ chối yêu cầu..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" 
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Từ chối
                    </button>
                    <button type="button" onclick="closeRejectionModal()" 
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-transparent text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Hủy
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Chức năng đánh dấu tất cả đã đọc
    document.getElementById('markAllRead').addEventListener('click', function() {
        if (confirm('Bạn có chắc chắn muốn đánh dấu tất cả thông báo là đã đọc?')) {
            fetch('{{ route("api.notifications.mark-all-read") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Hiển thị thông báo
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'fixed top-4 right-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-md z-50';
                    alertDiv.innerHTML = '<div class="flex items-center"><i class="fas fa-check-circle mr-2"></i><span>Đã đánh dấu tất cả thông báo là đã đọc!</span></div>';
                    document.body.appendChild(alertDiv);
                      // Đóng thông báo sau 1.5 giây
                    setTimeout(() => {
                        alertDiv.remove();
                        location.reload();
                    }, 1500);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    });
});

// Chức năng modal
function openApprovalModal(notificationId) {
    const modal = document.getElementById('approvalModal');
    const form = document.getElementById('approvalForm');
    form.action = `/notifications/${notificationId}/approve`;
    modal.classList.remove('hidden');
}

function closeApprovalModal() {
    const modal = document.getElementById('approvalModal');
    modal.classList.add('hidden');
    // Đặt lại form
    document.getElementById('approvalForm').reset();
}

function openRejectionModal(notificationId) {
    const modal = document.getElementById('rejectionModal');
    const form = document.getElementById('rejectionForm');
    form.action = `/notifications/${notificationId}/reject`;
    modal.classList.remove('hidden');
}

function closeRejectionModal() {
    const modal = document.getElementById('rejectionModal');
    modal.classList.add('hidden');
    // Đặt lại form
    document.getElementById('rejectionForm').reset();
}
</script>
@endpush
@endsection
