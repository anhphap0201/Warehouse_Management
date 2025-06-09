<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Store;
use App\Models\Product;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    /**
     * Hiển thị danh sách thông báo cho admin/manager
     */
    public function index(Request $request)
    {
        $query = Notification::with(['store', 'warehouse', 'approvedBy', 'rejectedBy']);
        
        // Lọc theo trạng thái nếu được cung cấp
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        $notifications = $query->orderBy('created_at', 'desc')
            ->paginate(20);

        // Bảo tồn tham số truy vấn trong liên kết phân trang
        $notifications->appends($request->query());

        // Lấy danh sách kho hàng cho modal phê duyệt
        $warehouses = Warehouse::get(['id', 'name', 'location']);

        return view('notifications.index', compact('notifications', 'warehouses'));
    }

    /**
     * Hiển thị form để tạo thông báo mới (yêu cầu từ cửa hàng)
     */
    public function create()
    {
        $stores = Store::where('status', true)->get();
        $products = Product::all();
        
        return view('notifications.create', compact('stores', 'products'));
    }

    /**
     * Lưu thông báo mới được tạo (yêu cầu từ cửa hàng)
     */
    public function store(Request $request)
    {
        $request->validate([
            'store_id' => 'required|exists:stores,id',
            'type' => 'required|in:receive_request,return_request',
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.reason' => 'nullable|string|max:500',
        ]);

        $notification = Notification::create([
            'store_id' => $request->store_id,
            'type' => $request->type,
            'title' => $request->title,
            'message' => $request->message,
            'data' => json_encode([
                'products' => $request->products,
                'requested_at' => now(),
                'priority' => $request->priority ?? 'normal'
            ]),
            'status' => 'pending',
        ]);

        // Xóa cache thông báo
        cache()->forget('unread_notifications_count');

        return redirect()->route('stores.show', $request->store_id)
            ->with('success', 'Yêu cầu đã được gửi thành công và đang chờ phê duyệt.');
    }

    /**
     * Hiển thị thông báo được chỉ định
     */
    public function show(Notification $notification)
    {
        $notification->load(['store', 'warehouse', 'approvedBy', 'rejectedBy']);
        
        // Đánh dấu là đã đọc nếu chưa đọc
        if (!$notification->read_at) {
            $notification->update([
                'read_at' => now(),
                'is_read' => true
            ]);
            // Xóa cache thông báo khi đánh dấu là đã đọc
            cache()->forget('unread_notifications_count');
        }

        // Lấy danh sách kho hàng để phê duyệt nếu thông báo vẫn đang chờ xử lý
        $warehouses = [];
        if ($notification->status === 'pending') {
            $warehouses = Warehouse::get(['id', 'name', 'location']);
        }

        return view('notifications.show', compact('notification', 'warehouses'));
    }

    /**
     * Phê duyệt thông báo với việc chỉ định kho hàng
     */
    public function approve(Request $request, Notification $notification)
    {
        if ($notification->status !== 'pending') {
            return redirect()->back()->with('error', 'Chỉ có thể phê duyệt thông báo đang chờ xử lý.');
        }

        $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'admin_response' => 'nullable|string|max:1000'
        ]);

        DB::transaction(function () use ($notification, $request) {
            $notification->update([
                'status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
                'warehouse_id' => $request->warehouse_id,
                'admin_response' => $request->admin_response,
                'admin_notes' => $request->admin_notes
            ]);

            // Xóa cache thông báo
            cache()->forget('unread_notifications_count');

            // Ở đây bạn có thể thêm logic để thực sự xử lý yêu cầu
            // Ví dụ: tạo chuyển động kho, cập nhật tồn kho, v.v.
            $this->processApprovedRequest($notification);
        });

        return redirect()->back()->with('success', 'Yêu cầu đã được phê duyệt và kho đã được chỉ định thành công.');
    }

    /**
     * Từ chối thông báo
     */
    public function reject(Request $request, Notification $notification)
    {
        if ($notification->status !== 'pending') {
            return redirect()->back()->with('error', 'Chỉ có thể từ chối thông báo đang chờ xử lý.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:1000'
        ]);

        $notification->update([
            'status' => 'rejected',
            'rejected_by' => Auth::id(),
            'rejected_at' => now(),
            'rejection_reason' => $request->rejection_reason,
            'admin_notes' => $request->admin_notes
        ]);

        // Xóa cache thông báo
        cache()->forget('unread_notifications_count');

        return redirect()->back()->with('success', 'Yêu cầu đã được từ chối.');
    }

    /**
     * Lấy số lượng thông báo chưa đọc cho chấm đỏ thông báo
     */
    public function getUnreadCount()
    {
        $count = Notification::pending()->whereNull('read_at')->count();
        
        return response()->json(['count' => $count]);
    }

    /**
     * Đánh dấu tất cả thông báo là đã đọc
     */
    public function markAllAsRead()
    {
        Notification::whereNull('read_at')
            ->update([
                'read_at' => now(),
                'is_read' => true
            ]);

        // Xóa cache thông báo
        cache()->forget('unread_notifications_count');

        return response()->json(['success' => true]);
    }

    /**
     * Xóa thông báo được chỉ định
     */
    public function destroy(Notification $notification)
    {
        $notification->delete();

        return redirect()->route('notifications.index')
            ->with('success', 'Thông báo đã được xóa thành công.');
    }

    /**
     * Lấy danh sách kho hàng có sẵn để phê duyệt
     */
    public function getWarehouses()
    {
        $warehouses = Warehouse::get(['id', 'name', 'location']);
        return response()->json($warehouses);
    }

    /**
     * Xử lý yêu cầu được phê duyệt - tạo chuyển động tồn kho thực tế
     */
    private function processApprovedRequest(Notification $notification)
    {
        $data = $notification->data;
        $type = $notification->type;
        
        // Dựa trên loại yêu cầu, tạo các bản ghi phù hợp
        switch ($type) {
            case 'receive_goods':
                // Logic để nhận hàng từ kho đến cửa hàng
                $this->processReceiveGoods($notification, $data);
                break;
                
            case 'return_goods':
                // Logic để trả hàng từ cửa hàng về kho
                $this->processReturnGoods($notification, $data);
                break;
                
            default:
                // Ghi log loại yêu cầu không xác định
                Log::warning("Unknown notification type: {$type}", ['notification_id' => $notification->id]);
                break;
        }
    }

    /**
     * Xử lý yêu cầu nhận hàng
     */
    private function processReceiveGoods(Notification $notification, array $data)
    {
        // Điều này sẽ tạo bản ghi chuyển động kho khi hàng hóa được nhận
        // Việc triển khai phụ thuộc vào hệ thống tồn kho của bạn
        Log::info("Processing receive goods request", [
            'notification_id' => $notification->id,
            'store_id' => $notification->store_id,
            'warehouse_id' => $notification->warehouse_id,
            'data' => $data
        ]);
    }    /**
     * Xử lý yêu cầu trả hàng
     */
    private function processReturnGoods(Notification $notification, array $data)
    {
        // Điều này sẽ tạo bản ghi chuyển động kho khi hàng hóa được trả lại
        // Việc triển khai phụ thuộc vào hệ thống tồn kho của bạn
        Log::info("Processing return goods request", [
            'notification_id' => $notification->id,
            'store_id' => $notification->store_id,
            'warehouse_id' => $notification->warehouse_id,
            'data' => $data
        ]);
    }
}
