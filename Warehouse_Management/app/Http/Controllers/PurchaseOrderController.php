<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Warehouse;
use App\Models\Product;
use App\Models\Inventory;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    /**
     * Hiển thị danh sách tài nguyên.
     */
    public function index(Request $request)
    {
        $query = PurchaseOrder::with(['warehouse', 'items'])
            ->orderBy('created_at', 'desc');

        // Bộ lọc tìm kiếm nâng cao
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

        // Hỗ trợ tìm kiếm legacy
        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhere('supplier_name', 'like', "%{$search}%");
            });
        }

        // Xử lý yêu cầu AJAX cho tìm kiếm thời gian thực
        if ($request->ajax() || $request->wantsJson()) {
            $purchaseOrders = $query->get();
            return response()->json([
                'data' => $purchaseOrders->map(function($order) {
                    return [
                        'id' => $order->id,
                        'invoice_number' => $order->invoice_number,
                        'supplier_name' => $order->supplier_name,
                        'status' => $order->status,
                        'total_amount' => $order->items->sum(function($item) {
                            return $item->quantity * $item->unit_price;
                        }),
                        'created_at' => $order->created_at,
                        'warehouse' => $order->warehouse ? [
                            'id' => $order->warehouse->id,
                            'name' => $order->warehouse->name
                        ] : null
                    ];
                }),
                'total' => $purchaseOrders->count(),
                'filters' => array_filter($filters)
            ]);
        }

        $purchaseOrders = $query->paginate(15);
        $warehouses = Warehouse::all();

        return view('purchase-orders.index', compact('purchaseOrders', 'warehouses'));
    }

    /**
     * Hiển thị form để tạo tài nguyên mới.
     */
    public function create()
    {
        $warehouses = Warehouse::all();
        $products = Product::all();
        $suppliers = Supplier::active()->get();
        
        return view('purchase-orders.create', compact('warehouses', 'products', 'suppliers'));
    }

    /**
     * Tạo số hóa đơn duy nhất
     */
    private function generateInvoiceNumber()
    {
        $prefix = 'PO';
        $date = date('Ymd');
        $lastOrder = PurchaseOrder::whereDate('created_at', today())
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
     * Lưu tài nguyên mới được tạo vào storage.
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
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1|max:999999999',
            'items.*.unit_price' => 'required|numeric|min:0|max:9999999999999.99',
        ], [
            // Thông báo lỗi tùy chỉnh
            'warehouse_id.required' => 'Vui lòng chọn kho hàng.',
            'warehouse_id.exists' => 'Kho hàng đã chọn không tồn tại.',
            'supplier_name.required' => 'Vui lòng nhập tên nhà cung cấp.',
            'items.required' => 'Vui lòng thêm ít nhất một sản phẩm.',
            'items.min' => 'Hóa đơn phải có ít nhất một sản phẩm.',
            'items.*.product_id.required' => 'Vui lòng chọn sản phẩm cho dòng :position.',
            'items.*.product_id.exists' => 'Sản phẩm ở dòng :position không tồn tại trong hệ thống.',
            'items.*.quantity.required' => 'Vui lòng nhập số lượng cho dòng :position.',
            'items.*.quantity.integer' => 'Số lượng phải là số nguyên.',
            'items.*.quantity.min' => 'Số lượng phải lớn hơn 0.',
            'items.*.quantity.max' => 'Số lượng không được vượt quá 999,999,999.',
            'items.*.unit_price.required' => 'Vui lòng nhập đơn giá cho dòng :position.',
            'items.*.unit_price.numeric' => 'Đơn giá phải là số.',
            'items.*.unit_price.min' => 'Đơn giá không được âm.',
            'items.*.unit_price.max' => 'Đơn giá không được vượt quá 9,999,999,999,999.99 VNĐ.',
        ]);

        $purchaseOrder = DB::transaction(function () use ($validated) {
            // Tính tổng tiền
            $totalAmount = 0;
            foreach ($validated['items'] as $item) {
                $totalAmount += $item['quantity'] * $item['unit_price'];
            }

            // Xác thực tổng tiền không vượt quá giới hạn database
            if ($totalAmount > 9999999999999.99) {
                throw new \Exception('Tổng tiền vượt quá giới hạn cho phép (9,999,999,999,999.99 VNĐ). Vui lòng giảm số lượng hoặc đơn giá.');
            }

            // Tạo đơn mua hàng với số hóa đơn tự động và trạng thái đã xác nhận
            $purchaseOrder = PurchaseOrder::create([
                'warehouse_id' => $validated['warehouse_id'],
                'supplier_id' => $validated['supplier_id'],
                'supplier_name' => $validated['supplier_name'],
                'supplier_phone' => $validated['supplier_phone'],
                'supplier_address' => $validated['supplier_address'],
                'invoice_number' => $this->generateInvoiceNumber(),
                'total_amount' => $totalAmount,
                'status' => 'confirmed', // Tự động xác nhận vì người dùng là admin
                'notes' => $validated['notes'],
            ]);

            // Tạo các item của đơn mua hàng
            foreach ($validated['items'] as $item) {
                PurchaseOrderItem::create([
                    'purchase_order_id' => $purchaseOrder->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['quantity'] * $item['unit_price'],
                ]);
            }

            // Tự động cập nhật tồn kho vì người dùng là admin - không cần xác nhận
            foreach ($validated['items'] as $item) {
                // Cập nhật hoặc tạo bản ghi tồn kho
                $inventory = Inventory::firstOrCreate(
                    [
                        'warehouse_id' => $validated['warehouse_id'],
                        'product_id' => $item['product_id'],
                    ],
                    ['quantity' => 0]
                );

                $inventory->increment('quantity', $item['quantity']);
            }

            return $purchaseOrder;
        });

        return redirect()->route('purchase-orders.show', $purchaseOrder)
            ->with('success', 'Hóa đơn nhập đã được tạo và hàng hóa đã được cập nhật vào kho thành công!');
    }

    /**
     * Hiển thị tài nguyên được chỉ định.
     */
    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['warehouse', 'items.product']);
        
        return view('purchase-orders.show', compact('purchaseOrder'));
    }

    /**
     * Hiển thị form để chỉnh sửa tài nguyên được chỉ định.
     * Vì tất cả đơn hàng đều được tự động xác nhận, việc chỉnh sửa không còn được phép.
     */
    public function edit(PurchaseOrder $purchaseOrder)
    {
        return redirect()->route('purchase-orders.show', $purchaseOrder)
            ->with('error', 'Không thể chỉnh sửa hóa đơn đã được xác nhận và cập nhật vào kho!');
    }

    /**
     * Cập nhật tài nguyên được chỉ định trong storage.
     * Vì tất cả đơn hàng đều được tự động xác nhận, việc cập nhật không được phép.
     */
    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        return redirect()->route('purchase-orders.show', $purchaseOrder)
            ->with('error', 'Không thể chỉnh sửa hóa đơn đã được xác nhận và cập nhật vào kho!');
    }

    /**
     * Xóa tài nguyên được chỉ định khỏi storage.
     * Vì tất cả đơn hàng đều được tự động xác nhận, việc xóa không được phép.
     */
    public function destroy(PurchaseOrder $purchaseOrder)
    {
        return redirect()->route('purchase-orders.index')
            ->with('error', 'Không thể xóa hóa đơn đã được xác nhận và cập nhật vào kho!');
    }

    /**
     * Tìm kiếm sản phẩm cho autocomplete
     */
    public function searchProducts(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 1) {
            return response()->json([]);
        }

        $products = Product::with('category')
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('sku', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->limit(10)
            ->get()
            ->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'code' => $product->sku, // Thêm trường code để tương thích
                    'category' => $product->category ? $product->category->name : null,
                    'unit' => $product->unit,
                    'description' => $product->description,
                ];
            });

        return response()->json($products);
    }

    /**
     * Tìm kiếm kho hàng cho autocomplete  
     */
    public function searchWarehouses(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 1) {
            return response()->json([]);
        }

        $warehouses = Warehouse::where('name', 'like', "%{$query}%")
            ->orWhere('location', 'like', "%{$query}%")
            ->limit(10)
            ->get()
            ->map(function($warehouse) {
                return [
                    'id' => $warehouse->id,
                    'name' => $warehouse->name,
                    'address' => $warehouse->location,
                ];
            });

        return response()->json($warehouses);
    }

    /**
     * Tìm kiếm nhà cung cấp cho autocomplete
     */
    public function searchSuppliers(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 1) {
            return response()->json([]);
        }

        $suppliers = PurchaseOrder::select('supplier_name', 'supplier_phone', 'supplier_address')
            ->distinct()
            ->where('supplier_name', 'like', "%{$query}%")
            ->limit(10)
            ->get()
            ->map(function($supplier) {
                return [
                    'name' => $supplier->supplier_name,
                    'phone' => $supplier->supplier_phone,
                    'address' => $supplier->supplier_address,
                ];
            });

        return response()->json($suppliers);
    }
}
