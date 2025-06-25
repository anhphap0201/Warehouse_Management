@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-4">
                <a href="{{ route('notifications.index') }}" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-800">Chi tiết thông báo</h1>
            </div>
            
            @if($notification->status === 'pending')
                <div class="flex space-x-3">
                    <form action="{{ route('notifications.approve', $notification) }}" method="POST" class="inline">
                        @csrf
                        <button type="button" onclick="showApprovalModal()" 
                                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition duration-200">
                            <i class="fas fa-check mr-2"></i>Phê duyệt
                        </button>
                    </form>
                    
                    <form action="{{ route('notifications.reject', $notification) }}" method="POST" class="inline">
                        @csrf
                        <button type="button" onclick="showRejectionModal()"
                                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition duration-200">
                            <i class="fas fa-times mr-2"></i>Từ chối
                        </button>
                    </form>
                </div>
            @endif
        </div>

        <!-- Notification Details Card -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <!-- Header Section -->
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4 text-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        @if($notification->type === 'receive_request')
                            <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                                <i class="fas fa-arrow-down text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-semibold">Yêu cầu nhận hàng</h2>
                                <p class="text-blue-100">Cửa hàng yêu cầu nhận hàng từ kho</p>
                            </div>
                        @else
                            <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                                <i class="fas fa-arrow-up text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-semibold">Yêu cầu trả hàng</h2>
                                <p class="text-blue-100">Cửa hàng yêu cầu trả hàng về kho</p>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Status Badge -->
                    @if($notification->status === 'pending')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                            <i class="fas fa-clock mr-1"></i>Chờ xử lý
                        </span>
                    @elseif($notification->status === 'approved')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check mr-1"></i>Đã phê duyệt
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            <i class="fas fa-times mr-1"></i>Đã từ chối
                        </span>
                    @endif
                </div>
            </div>

            <!-- Content Section -->
            <div class="p-6">
                <!-- Basic Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Thông tin cơ bản</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between py-1">
                                <span class="text-gray-600">Tiêu đề:</span>
                                <span class="font-medium">{{ $notification->title }}</span>
                            </div>
                            <div class="flex justify-between py-1">
                                <span class="text-gray-600">Cửa hàng:</span>
                                <span class="font-medium">{{ $notification->store->name }}</span>
                            </div>                            <div class="flex justify-between py-1">
                                <span class="text-gray-600">Địa chỉ:</span>
                                <span class="font-medium">{{ $notification->store->location }}</span>
                            </div>
                            <div class="flex justify-between py-1">
                                <span class="text-gray-600">Ngày tạo:</span>
                                <span class="font-medium">{{ $notification->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            @if($notification->warehouse)
                                <div class="flex justify-between py-1">
                                    <span class="text-gray-600">Kho được phân:</span>
                                    <span class="font-medium text-blue-600">{{ $notification->warehouse->name }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Thời gian</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between py-1">
                                <span class="text-gray-600">Ngày tạo:</span>
                                <span class="font-medium">{{ $notification->created_at->format('d/m/Y H:i') }}</span>
                            </div>                            @if($notification->approved_at)
                                <div class="flex justify-between py-1">
                                    <span class="text-gray-600">Ngày phê duyệt:</span>
                                    <span class="font-medium">{{ $notification->approved_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="flex justify-between py-1">
                                    <span class="text-gray-600">Người phê duyệt:</span>
                                    <span class="font-medium">{{ $notification->approvedBy->name ?? 'N/A' }}</span>
                                </div>
                            @elseif($notification->rejected_at)
                                <div class="flex justify-between py-1">
                                    <span class="text-gray-600">Ngày từ chối:</span>
                                    <span class="font-medium">{{ $notification->rejected_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="flex justify-between py-1">
                                    <span class="text-gray-600">Người từ chối:</span>
                                    <span class="font-medium">{{ $notification->rejectedBy->name ?? 'N/A' }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Message -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Nội dung yêu cầu</h3>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-gray-700">{{ $notification->message }}</p>
                    </div>
                </div>                <!-- Admin Response -->
                @if($notification->status === 'approved' && $notification->admin_response)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-green-600 mb-3">Phản hồi quản lý</h3>
                        <div class="bg-green-50 border border-green-200 p-4 rounded-lg">
                            <p class="text-green-700">{{ $notification->admin_response }}</p>
                        </div>
                    </div>
                @endif

                <!-- Rejection Reason -->
                @if($notification->status === 'rejected' && $notification->rejection_reason)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-red-600 mb-3">Lý do từ chối</h3>
                        <div class="bg-red-50 border border-red-200 p-4 rounded-lg">
                            <p class="text-red-700">{{ $notification->rejection_reason }}</p>
                        </div>
                    </div>
                @endif

                <!-- Admin Notes -->
                @if($notification->admin_notes)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Ghi chú của quản trị viên</h3>
                        <div class="bg-blue-50 border border-blue-200 p-4 rounded-lg">
                            <p class="text-blue-700">{{ $notification->admin_notes }}</p>
                        </div>
                    </div>
                @endif                <!-- Products List -->
                @if($notification->data && isset($notification->data['products']))
                    @php
                        $data = $notification->data;
                        $products = $data['products'] ?? [];
                    @endphp
                    
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Danh sách sản phẩm</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sản phẩm</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số lượng</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lý do</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($products as $productData)
                                        @php
                                            $product = \App\Models\Product::find($productData['product_id']);
                                        @endphp
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $product->name ?? 'Sản phẩm không tồn tại' }}</div>
                                                <div class="text-sm text-gray-500">{{ $product->sku ?? '' }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ number_format($productData['quantity']) }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                {{ $productData['reason'] ?? 'Không có lý do' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <!-- Admin Notes -->
                @if($notification->admin_notes)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Ghi chú từ quản lý</h3>
                        <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-lg">
                            <p class="text-gray-700">{{ $notification->admin_notes }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Approval Modal -->
<div id="approvalModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                <i class="fas fa-check text-green-600"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mt-2">Phê duyệt yêu cầu</h3>
            <div class="mt-2 px-7 py-3">
                <form id="approvalForm" action="{{ route('notifications.approve', $notification) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Chọn kho xử lý *</label>
                        <select name="warehouse_id" class="w-full p-2 border border-gray-300 rounded-md" required>
                            <option value="">-- Chọn kho --</option>
                            @foreach($warehouses ?? [] as $warehouse)
                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phản hồi cho cửa hàng *</label>
                        <textarea name="admin_response" rows="3" class="w-full p-2 border border-gray-300 rounded-md" placeholder="Nhập phản hồi cho cửa hàng..." required></textarea>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeApprovalModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                            Hủy
                        </button>
                        <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                            Phê duyệt
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Rejection Modal -->
<div id="rejectionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <i class="fas fa-times text-red-600"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mt-2">Từ chối yêu cầu</h3>
            <div class="mt-2 px-7 py-3">
                <form id="rejectionForm" action="{{ route('notifications.reject', $notification) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Lý do từ chối *</label>
                        <textarea name="rejection_reason" rows="3" class="w-full p-2 border border-gray-300 rounded-md" placeholder="Nhập lý do từ chối..." required></textarea>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeRejectionModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                            Hủy
                        </button>
                        <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">
                            Từ chối
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showApprovalModal() {
    document.getElementById('approvalModal').classList.remove('hidden');
}

function closeApprovalModal() {
    document.getElementById('approvalModal').classList.add('hidden');
}

function showRejectionModal() {
    document.getElementById('rejectionModal').classList.remove('hidden');
}

function closeRejectionModal() {
    document.getElementById('rejectionModal').classList.add('hidden');
}

// Đóng modal khi click bên ngoài
window.onclick = function(event) {
    const approvalModal = document.getElementById('approvalModal');
    const rejectionModal = document.getElementById('rejectionModal');
    
    if (event.target == approvalModal) {
        closeApprovalModal();
    }
    if (event.target == rejectionModal) {
        closeRejectionModal();
    }
}
</script>
@endpush
@endsection
