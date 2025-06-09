<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{    /**
     * Hiển thị danh sách các kho hàng.
     */
    public function index()
    {
        $warehouses = Warehouse::latest()->paginate(15);
        return view('warehouses.index', compact('warehouses'));
    }    /**
     * Hiển thị form để tạo kho hàng mới.
     */
    public function create()
    {
        return view('warehouses.create');
    }    /**
     * Lưu kho hàng mới được tạo vào storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
        ]);

        Warehouse::create($request->all());

        return redirect()->route('warehouses.index')
            ->with('success', 'Kho hàng đã được tạo thành công!');    }    /**
     * Hiển thị kho hàng được chỉ định.
     */    public function show(Request $request, Warehouse $warehouse)
    {
        // Tải tồn kho với các mối quan hệ sản phẩm và danh mục
        $warehouse->load(['inventory.product.category']);
        
        // Lấy tất cả danh mục có sản phẩm trong kho này
        $categories = \App\Models\Category::whereHas('products.inventory', function($query) use ($warehouse) {
            $query->where('warehouse_id', $warehouse->id);
        })->get();
        
        // Lọc tồn kho theo danh mục nếu được yêu cầu
        $categoryFilter = $request->get('category_id');
        $filteredInventory = $warehouse->inventory;
        
        if ($categoryFilter) {
            $filteredInventory = $warehouse->inventory->filter(function($inventory) use ($categoryFilter) {
                return $inventory->product->category_id == $categoryFilter;
            });
        }
        
        return view('warehouses.show', compact('warehouse', 'categories', 'filteredInventory', 'categoryFilter'));
    }    /**
     * Hiển thị form để chỉnh sửa kho hàng được chỉ định.
     */
    public function edit(Warehouse $warehouse)
    {
        return view('warehouses.edit', compact('warehouse'));
    }    /**
     * Cập nhật kho hàng được chỉ định trong storage.
     */
    public function update(Request $request, Warehouse $warehouse)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
        ]);

        $warehouse->update($request->all());

        return redirect()->route('warehouses.index')
            ->with('success', 'Kho hàng đã được cập nhật thành công!');
    }    /**
     * Xóa kho hàng được chỉ định khỏi storage.
     */
    public function destroy(Warehouse $warehouse)
    {
        $warehouse->delete();

        return redirect()->route('warehouses.index')
            ->with('success', 'Kho hàng đã được xóa thành công!');
    }    /**
     * Lấy thông tin chi tiết kho hàng cho các lời gọi API
     */
    public function getWarehouse(Warehouse $warehouse)
    {
        return response()->json([
            'id' => $warehouse->id,
            'name' => $warehouse->name,
            'location' => $warehouse->location,
        ]);
    }
}
