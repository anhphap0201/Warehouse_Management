@extends('layouts.app')

@section('content')
<div class="page-container">
    <div class="page-content">
        <!-- Page Header -->
        <div class="page-header-standard">
            <div class="page-header-content">
                <div class="page-title-section">
                    <h1 class="page-title-main text-gray-900">
                        <div class="page-title-icon simple-bg">
                            <i class="fas fa-bell text-blue-600 text-lg"></i>
                        </div>
                        {{ __('Quản lý Thông báo') }}
                    </h1>
                    <p class="page-subtitle">Quản lý và theo dõi tất cả thông báo trong hệ thống</p>
                </div>
                <div class="page-actions">
                    <button id="markAllRead" class="btn-primary-standard">
                        <i class="fas fa-check-double mr-2"></i>
                        {{ __('Đánh dấu tất cả đã đọc') }}
                    </button>
                </div>
            </div>
        </div>
        
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
        @endif        <!-- Filter tabs with improved design -->
        <div class="mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-1">
                <nav class="flex flex-wrap gap-1 sm:gap-2">
                    <a href="{{ route('notifications.index') }}" 
                       class="filter-tab {{ request('status') === null ? 'filter-tab-active' : 'filter-tab-inactive' }}">
                        <i class="fas fa-list mr-2"></i>
                        Tất cả
                        <span class="badge-count">{{ $notifications->count() }}</span>
                    </a>
                    <a href="{{ route('notifications.index', ['status' => 'pending']) }}" 
                       class="filter-tab {{ request('status') === 'pending' ? 'filter-tab-active' : 'filter-tab-inactive' }}">
                        <i class="fas fa-clock mr-2"></i>
                        Chờ xử lý
                        @if($notifications->where('status', 'pending')->count() > 0)
                            <span class="badge-count bg-red-100 text-red-800 animate-pulse">
                                {{ $notifications->where('status', 'pending')->count() }}
                            </span>
                        @endif
                    </a>
                    <a href="{{ route('notifications.index', ['status' => 'approved']) }}" 
                       class="filter-tab {{ request('status') === 'approved' ? 'filter-tab-active' : 'filter-tab-inactive' }}">
                        <i class="fas fa-check mr-2"></i>
                        Đã phê duyệt
                    </a>
                    <a href="{{ route('notifications.index', ['status' => 'rejected']) }}" 
                       class="filter-tab {{ request('status') === 'rejected' ? 'filter-tab-active' : 'filter-tab-inactive' }}">
                        <i class="fas fa-times mr-2"></i>
                        Đã từ chối
                    </a>                </nav>
                
                <div class="tab-divider"></div>
            </div>
        </div>

        @if($notifications->count() > 0)
            <!-- Notifications Table -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <div class="section-divider">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                            <i class="fas fa-bell mr-2 text-blue-600"></i>
                            Danh sách thông báo
                        </h3>
                    </div>

                    <div class="table-wrapper">
                <div class="table-wrapper">
                    <!-- Table View -->
                    <table class="table-modern">
                        <thead>
                            <tr>
                                <th>Thông báo</th>
                                <th>Cửa hàng</th>
                                <th>Kho</th>
                                <th>Loại</th>
                                <th>Trạng thái</th>
                                <th>Ngày tạo</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>                            @foreach($notifications as $notification)
                                <tr class="notification-row {{ !$notification->read_at ? 'notification-unread' : '' }} transition-colors duration-200">
                                    <td>
                                        <div class="flex items-center">
                                            <div class="notification-icon {{ !$notification->read_at ? 'notification-icon-unread' : 'notification-icon-read' }}">
                                                @if($notification->type === 'receive_request')
                                                    <i class="fas fa-arrow-down text-green-600"></i>
                                                @else
                                                    <i class="fas fa-arrow-up text-orange-600"></i>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900 flex items-center">
                                                    {{ $notification->title }}
                                                    @if(!$notification->read_at)
                                                        <span class="notification-pulse">
                                                            <span class="animate-ping absolute inline-flex h-2 w-2 rounded-full bg-red-400 opacity-75"></span>
                                                            <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="text-sm text-gray-500">{{ Str::limit($notification->message, 60) }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-sm text-gray-900">
                                            <i class="fas fa-store mr-1 text-blue-600"></i>
                                            {{ $notification->store->name }}
                                        </div>
                                    </td>
                                    <td>
                                        @if($notification->warehouse)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $notification->warehouse->name }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 text-sm">Chưa có</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($notification->type === 'receive_request')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-arrow-down mr-1"></i>
                                                Nhập kho
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                <i class="fas fa-arrow-up mr-1"></i>
                                                Xuất kho
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($notification->status === 'pending')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-clock mr-1"></i>
                                                Chờ xử lý
                                            </span>
                                        @elseif($notification->status === 'approved')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check mr-1"></i>
                                                Đã phê duyệt
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-times mr-1"></i>
                                                Đã từ chối
                                            </span>
                                        @endif
                                        
                                        @if(!$notification->read_at)
                                            <div class="mt-1">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <i class="fas fa-bell mr-1"></i>
                                                    Chưa đọc
                                                </span>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="text-sm text-gray-500">
                                            <div>{{ $notification->created_at->format('d/m/Y') }}</div>
                                            <div class="text-xs text-gray-400">{{ $notification->created_at->format('H:i') }}</div>
                                        </div>
                                    </td>
                                    <td class="table-actions">
                                        <div class="action-buttons">
                                            <a href="{{ route('notifications.show', $notification) }}" 
                                               class="action-btn action-btn-view"
                                               title="Xem chi tiết">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            @if($notification->status === 'pending')
                                                <button type="button" 
                                                        onclick="openApprovalModal({{ $notification->id }})" 
                                                        class="action-btn action-btn-edit"
                                                        title="Phê duyệt">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button type="button" 
                                                        onclick="openRejectionModal({{ $notification->id }})"
                                                        class="action-btn action-btn-delete"
                                                        title="Từ chối">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>                    </table>

                    <!-- Pagination -->
                    @if($notifications->hasPages())
                        <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                            {{ $notifications->links() }}
                        </div>
                    @endif
                    </div>
                </div>
            </div>        @else
            <div class="empty-state">
                <div class="empty-state-content">
                    <div class="empty-state-icon">
                        <i class="fas fa-bell text-6xl"></i>
                    </div>
                    <h3 class="empty-state-title">Chưa có thông báo nào</h3>
                    <p class="empty-state-description">
                        Chưa có thông báo nào được tạo trong hệ thống. Các thông báo sẽ xuất hiện ở đây khi có yêu cầu từ cửa hàng.
                    </p>
                    <div class="empty-state-action">
                        <a href="{{ route('dashboard') }}" class="btn-primary-standard">
                            <i class="fas fa-home mr-2"></i>
                            Về trang chủ
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Modern Approval Modal -->
<div id="approvalModal" class="modal-overlay hidden">
    <div class="modal-container">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon modal-icon-success">
                    <i class="fas fa-check"></i>
                </div>
                <h3 class="modal-title">Phê duyệt yêu cầu</h3>
                <p class="modal-description">Bạn có chắc chắn muốn phê duyệt yêu cầu này không?</p>
            </div>
            <form id="approvalForm" method="POST" class="modal-form">
                @csrf
                <div class="form-group">
                    <label for="warehouse_id" class="form-label">Chọn kho</label>
                    <select name="warehouse_id" id="warehouse_id" required class="form-select">
                        <option value="">Chọn kho...</option>
                        @foreach(\App\Models\Warehouse::all() as $warehouse)
                            <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
            <div class="modal-actions">
                <button id="confirmApproval" class="btn-modal btn-modal-success">
                    <i class="fas fa-check mr-2"></i>
                    Phê duyệt
                </button>
                <button id="cancelApproval" class="btn-modal btn-modal-cancel">
                    <i class="fas fa-times mr-2"></i>
                    Hủy
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modern Rejection Modal -->
<div id="rejectionModal" class="modal-overlay hidden">
    <div class="modal-container">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon modal-icon-danger">
                    <i class="fas fa-times"></i>
                </div>
                <h3 class="modal-title">Từ chối yêu cầu</h3>
                <p class="modal-description">Bạn có chắc chắn muốn từ chối yêu cầu này không?</p>
            </div>
            <form id="rejectionForm" method="POST" class="modal-form">
                @csrf
                <div class="form-group">
                    <label for="rejection_reason" class="form-label">Lý do từ chối</label>
                    <textarea name="rejection_reason" id="rejection_reason" rows="3" 
                              class="form-textarea" 
                              placeholder="Nhập lý do từ chối..."></textarea>
                </div>
            </form>
            <div class="modal-actions">
                <button id="confirmRejection" class="btn-modal btn-modal-danger">
                    <i class="fas fa-times mr-2"></i>
                    Từ chối
                </button>
                <button id="cancelRejection" class="btn-modal btn-modal-cancel">
                    <i class="fas fa-undo mr-2"></i>
                    Hủy
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Đảm bảo DOM đã load xong
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing notification page scripts...');
    
    // Modal functions
    window.openApprovalModal = function(notificationId) {
        console.log('Opening approval modal for notification:', notificationId);
        const modal = document.getElementById('approvalModal');
        const form = document.getElementById('approvalForm');
        if (modal && form) {
            modal.classList.remove('hidden');
            form.action = `/notifications/${notificationId}/approve`;
        }
    };

    window.openRejectionModal = function(notificationId) {
        console.log('Opening rejection modal for notification:', notificationId);
        const modal = document.getElementById('rejectionModal');
        const form = document.getElementById('rejectionForm');
        if (modal && form) {
            modal.classList.remove('hidden');
            form.action = `/notifications/${notificationId}/reject`;
        }
    };

    // Cancel buttons
    const cancelApproval = document.getElementById('cancelApproval');
    if (cancelApproval) {
        cancelApproval.addEventListener('click', function() {
            console.log('Closing approval modal');
            document.getElementById('approvalModal').classList.add('hidden');
        });
    }

    const cancelRejection = document.getElementById('cancelRejection');
    if (cancelRejection) {
        cancelRejection.addEventListener('click', function() {
            console.log('Closing rejection modal');
            document.getElementById('rejectionModal').classList.add('hidden');
        });
    }

    // Confirm buttons
    const confirmApproval = document.getElementById('confirmApproval');
    if (confirmApproval) {
        confirmApproval.addEventListener('click', function() {
            console.log('Submitting approval form');
            const form = document.getElementById('approvalForm');
            if (form) {
                form.submit();
            }
        });
    }

    const confirmRejection = document.getElementById('confirmRejection');
    if (confirmRejection) {
        confirmRejection.addEventListener('click', function() {
            console.log('Submitting rejection form');
            const form = document.getElementById('rejectionForm');
            if (form) {
                form.submit();
            }
        });
    }

    // Mark all as read functionality
    const markAllRead = document.getElementById('markAllRead');
    if (markAllRead) {
        markAllRead.addEventListener('click', function() {
            console.log('Mark all as read clicked');
            if (confirm('Bạn có chắc chắn muốn đánh dấu tất cả thông báo là đã đọc?')) {
                fetch('{{ route("notifications.mark-all-read") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error marking all as read:', error);
                });
            }
        });
    }

    // Close modal when clicking overlay
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal-overlay')) {
            console.log('Clicked modal overlay, closing modal');
            e.target.classList.add('hidden');
        }
    });

    console.log('Notification page scripts initialized successfully');
    
    // Debug: Test click handlers
    console.log('Testing click handlers...');
    console.log('markAllRead button:', document.getElementById('markAllRead'));
    console.log('Filter tabs:', document.querySelectorAll('.filter-tab'));
    console.log('Action buttons:', document.querySelectorAll('.action-btn'));
    
    // Add temporary debug class to clickable elements
    if (window.location.search.includes('debug=1')) {
        document.querySelectorAll('.filter-tab, .btn-primary-standard, .action-btn').forEach(el => {
            el.classList.add('debug-clickable');
        });
    }
});
</script>
@endsection
