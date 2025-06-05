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
     * Display a listing of notifications for admin/manager
     */
    public function index(Request $request)
    {
        $query = Notification::with(['store', 'warehouse', 'approvedBy', 'rejectedBy']);
        
        // Filter by status if provided
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        $notifications = $query->orderBy('created_at', 'desc')
            ->paginate(20);

        // Preserve query parameters in pagination links
        $notifications->appends($request->query());

        // Get warehouses for approval modal
        $warehouses = Warehouse::get(['id', 'name', 'location']);

        return view('notifications.index', compact('notifications', 'warehouses'));
    }

    /**
     * Show the form for creating a new notification (store request)
     */
    public function create()
    {
        $stores = Store::where('status', true)->get();
        $products = Product::all();
        
        return view('notifications.create', compact('stores', 'products'));
    }

    /**
     * Store a newly created notification (store request)
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
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('stores.show', $request->store_id)
            ->with('success', 'Yêu cầu đã được gửi thành công và đang chờ phê duyệt.');
    }

    /**
     * Display the specified notification
     */
    public function show(Notification $notification)
    {
        $notification->load(['store', 'warehouse', 'approvedBy', 'rejectedBy', 'createdBy']);
        
        // Mark as read if not read yet
        if (!$notification->read_at) {
            $notification->update(['read_at' => now()]);
        }

        // Get warehouses for approval if notification is still pending
        $warehouses = [];
        if ($notification->status === 'pending') {
            $warehouses = Warehouse::get(['id', 'name', 'location']);
        }

        return view('notifications.show', compact('notification', 'warehouses'));
    }

    /**
     * Approve a notification with warehouse assignment
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

            // Here you can add logic to actually process the request
            // For example, create stock movements, update inventory, etc.
            $this->processApprovedRequest($notification);
        });

        return redirect()->back()->with('success', 'Yêu cầu đã được phê duyệt và kho đã được chỉ định thành công.');
    }

    /**
     * Reject a notification
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

        return redirect()->back()->with('success', 'Yêu cầu đã được từ chối.');
    }

    /**
     * Get unread notifications count for red dot indicator
     */
    public function getUnreadCount()
    {
        $count = Notification::pending()->whereNull('read_at')->count();
        
        return response()->json(['count' => $count]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        Notification::whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    /**
     * Remove the specified notification
     */
    public function destroy(Notification $notification)
    {
        $notification->delete();

        return redirect()->route('notifications.index')
            ->with('success', 'Thông báo đã được xóa thành công.');
    }

    /**
     * Get available warehouses for approval
     */
    public function getWarehouses()
    {
        $warehouses = Warehouse::get(['id', 'name', 'location']);
        return response()->json($warehouses);
    }

    /**
     * Process approved request - create actual inventory movement
     */
    private function processApprovedRequest(Notification $notification)
    {
        $data = $notification->data;
        $type = $notification->type;
        
        // Based on request type, create appropriate records
        switch ($type) {
            case 'receive_goods':
                // Logic for receiving goods from warehouse to store
                $this->processReceiveGoods($notification, $data);
                break;
                
            case 'return_goods':
                // Logic for returning goods from store to warehouse
                $this->processReturnGoods($notification, $data);
                break;
                
            default:
                // Log unknown request type
                Log::warning("Unknown notification type: {$type}", ['notification_id' => $notification->id]);
                break;
        }
    }

    /**
     * Process receive goods request
     */
    private function processReceiveGoods(Notification $notification, array $data)
    {
        // This would create stock movement records when goods are received
        // Implementation depends on your inventory system
        Log::info("Processing receive goods request", [
            'notification_id' => $notification->id,
            'store_id' => $notification->store_id,
            'warehouse_id' => $notification->warehouse_id,
            'data' => $data
        ]);
    }

    /**
     * Process return goods request
     */
    private function processReturnGoods(Notification $notification, array $data)
    {
        // This would create stock movement records when goods are returned
        // Implementation depends on your inventory system
        Log::info("Processing return goods request", [
            'notification_id' => $notification->id,
            'store_id' => $notification->store_id,
            'warehouse_id' => $notification->warehouse_id,
            'data' => $data
        ]);
    }
}
