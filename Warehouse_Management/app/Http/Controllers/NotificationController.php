<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Store;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    /**
     * Display a listing of notifications for admin/manager
     */
    public function index(Request $request)
    {
        $query = Notification::with(['store', 'approvedBy', 'rejectedBy']);
        
        // Filter by status if provided
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        $notifications = $query->orderBy('created_at', 'desc')
            ->paginate(20);

        // Preserve query parameters in pagination links
        $notifications->appends($request->query());

        return view('notifications.index', compact('notifications'));
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
        $notification->load(['store', 'approvedBy', 'createdBy']);
        
        // Mark as read if not read yet
        if (!$notification->read_at) {
            $notification->update(['read_at' => now()]);
        }

        return view('notifications.show', compact('notification'));
    }

    /**
     * Approve a notification
     */
    public function approve(Notification $notification)
    {
        if ($notification->status !== 'pending') {
            return redirect()->back()->with('error', 'Chỉ có thể phê duyệt thông báo đang chờ xử lý.');
        }

        DB::transaction(function () use ($notification) {
            $notification->update([
                'status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
                'admin_notes' => request('admin_notes')
            ]);

            // Here you can add logic to actually process the request
            // For example, create stock movements, update inventory, etc.
        });

        return redirect()->back()->with('success', 'Yêu cầu đã được phê duyệt thành công.');
    }

    /**
     * Reject a notification
     */
    public function reject(Notification $notification)
    {
        if ($notification->status !== 'pending') {
            return redirect()->back()->with('error', 'Chỉ có thể từ chối thông báo đang chờ xử lý.');
        }

        $notification->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'admin_notes' => request('admin_notes')
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
}
