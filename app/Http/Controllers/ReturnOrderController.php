<?php

namespace App\Http\Controllers;

use App\Models\ReturnOrder;
use App\Models\ReturnOrderItem;
use App\Models\Warehouse;
use App\Models\Product;
use App\Models\Inventory;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturnOrderController extends Controller
{
    /**
     * Hiển thị danh sách đơn trả hàng.
     */
    public function index(Request $request)
    {
        $query = ReturnOrder::with(['warehouse', 'items'])
            ->orderBy('created_at', 'desc');

        // Bộ lọc tìm kiếm
        $filters = [
            'invoice_number' => $request->input('invoice_number'),
            'warehouse' => $request->input('warehouse'),
            'supplier' => $request->input('supplier'),
            'status' => $request->input('status')
        ];

        // Áp dụng các bộ lọc
        if ($filters['invoice_number']) {
            $query->where('invoice_number', 'like', "%{$filters['invoice_number']}%");
        }

        if ($filters['warehouse']) {
            $query->whereHas('warehouse', function($q) use ($filters) {
                $q->where('name', 'like', "%{$filters['warehouse']}%");
            });
        }

        if ($filters['supplier']) {
            $query->where('supplier_name', 'like', "%{$filters['supplier']}%");
        }

        if ($filters['status']) {
            $query->where('status', $filters['status']);
        }

        $returnOrders = $query->paginate(20);
        $returnOrders->appends($filters);

        $warehouses = Warehouse::select('id', 'name')->get();

        return view('return-orders.index', compact('returnOrders', 'warehouses', 'filters'));
    }

    /**
     * Hiển thị form tạo đơn trả hàng mới.
     */
    public function create()
    {
        $warehouses = Warehouse::all();
        $products = Product::all();
        $suppliers = Supplier::active()->get();
        
        return view('return-orders.create', compact('warehouses', 'products', 'suppliers'));
    }

    /**
     * Tạo số hóa đơn trả hàng duy nhất
     */
    private function generateInvoiceNumber()
    {
        $prefix = 'RO';
        $date = date('Ymd');
        $lastOrder = ReturnOrder::whereDate('created_at', today())
            ->where('invoice_number', 'like', $prefix . $date . '%')
            ->orderBy('invoice_number', 'desc')
            ->first();

        if ($lastOrder) {
            $lastNumber = intval(substr($lastOrder->invoice_number, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return $prefix . $date . $newNumber;
    }

    /**
     * Lưu đơn trả hàng mới.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'supplier_name' => 'required|string|max:255',
            'supplier_phone' => 'nullable|string|max:20',
            'supplier_address' => 'nullable|string',
            'notes' => 'nullable|string',
            'return_reason' => 'required|string|max:500',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1|max:999999999',
            'items.*.unit_price' => 'required|numeric|min:0|max:999999999999.99',
            'items.*.return_reason' => 'nullable|string|max:255',
        ], [
            'warehouse_id.required' => 'Vui lòng chọn kho hàng.',
            'supplier_name.required' => 'Vui lòng nhập tên nhà cung cấp.',
            'return_reason.required' => 'Vui lòng nhập lý do trả hàng.',
            'items.required' => 'Vui lòng thêm ít nhất một sản phẩm.',
            'items.min' => 'Đơn trả phải có ít nhất một sản phẩm.',
        ]);

        $returnOrder = DB::transaction(function () use ($validated) {
            // Tính tổng tiền
            $totalAmount = 0;
            foreach ($validated['items'] as $item) {
                $totalAmount += $item['quantity'] * $item['unit_price'];
            }

            // Tạo đơn trả hàng (chỉ lưu thông tin, không cập nhật kho)
            $returnOrder = ReturnOrder::create([
                'warehouse_id' => $validated['warehouse_id'],
                'supplier_id' => $validated['supplier_id'],
                'supplier_name' => $validated['supplier_name'],
                'supplier_phone' => $validated['supplier_phone'],
                'supplier_address' => $validated['supplier_address'],
                'invoice_number' => $this->generateInvoiceNumber(),
                'total_amount' => $totalAmount,
                'status' => 'pending', // Chờ xử lý
                'notes' => $validated['notes'],
                'return_reason' => $validated['return_reason'],
            ]);

            // Tạo các item của đơn trả hàng
            foreach ($validated['items'] as $item) {
                ReturnOrderItem::create([
                    'return_order_id' => $returnOrder->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['quantity'] * $item['unit_price'],
                    'return_reason' => $item['return_reason'] ?? null,
                ]);
            }

            // KHÔNG cập nhật kho ở đây - chỉ lưu thông tin đơn trả

            return $returnOrder;
        });

        return redirect()->route('return-orders.show', $returnOrder)
            ->with('success', 'Đơn trả hàng đã được tạo thành công! Hàng hóa chưa được trừ khỏi kho.');
    }

    /**
     * Hiển thị chi tiết đơn trả hàng.
     */
    public function show(ReturnOrder $returnOrder)
    {
        $returnOrder->load(['warehouse', 'items.product']);
        
        return view('return-orders.show', compact('returnOrder'));
    }

    /**
     * Xử lý đơn trả hàng - trừ hàng khỏi kho
     */
    public function process(ReturnOrder $returnOrder)
    {
        if ($returnOrder->status !== 'pending') {
            return redirect()->back()->with('error', 'Chỉ có thể xử lý đơn trả hàng đang chờ.');
        }

        DB::transaction(function () use ($returnOrder) {
            // Trừ hàng khỏi kho
            foreach ($returnOrder->items as $item) {
                $inventory = Inventory::where('warehouse_id', $returnOrder->warehouse_id)
                    ->where('product_id', $item->product_id)
                    ->first();

                if ($inventory && $inventory->quantity >= $item->quantity) {
                    $inventory->decrement('quantity', $item->quantity);
                } else {
                    throw new \Exception("Không đủ hàng trong kho để trả: {$item->product->name}");
                }
            }

            // Cập nhật trạng thái đơn trả
            $returnOrder->update([
                'status' => 'processed',
                'processed_at' => now()
            ]);
        });

        return redirect()->back()->with('success', 'Đã xử lý đơn trả hàng và trừ hàng khỏi kho!');
    }

    /**
     * Hủy đơn trả hàng
     */
    public function cancel(ReturnOrder $returnOrder)
    {
        if ($returnOrder->status === 'processed') {
            return redirect()->back()->with('error', 'Không thể hủy đơn trả đã được xử lý.');
        }

        $returnOrder->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', 'Đã hủy đơn trả hàng.');
    }

    /**
     * Xóa đơn trả hàng
     */
    public function destroy(ReturnOrder $returnOrder)
    {
        if ($returnOrder->status === 'processed') {
            return redirect()->back()->with('error', 'Không thể xóa đơn trả đã được xử lý.');
        }

        $returnOrder->delete();

        return redirect()->route('return-orders.index')
            ->with('success', 'Đã xóa đơn trả hàng.');
    }
}
