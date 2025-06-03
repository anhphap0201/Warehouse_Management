<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the warehouses.
     */
    public function index()
    {
        $warehouses = Warehouse::latest()->get();
        return view('warehouses.index', compact('warehouses'));
    }

    /**
     * Show the form for creating a new warehouse.
     */
    public function create()
    {
        return view('warehouses.create');
    }

    /**
     * Store a newly created warehouse in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
        ]);

        Warehouse::create($request->all());

        return redirect()->route('warehouses.index')
            ->with('success', 'Kho hàng đã được tạo thành công!');
    }    /**
     * Display the specified warehouse.
     */
    public function show(Request $request, Warehouse $warehouse)
    {
        // Load inventory with product and category relationships
        $warehouse->load(['inventory.product.category']);
        
        // Get all categories that have products in this warehouse
        $categories = \App\Models\Category::whereHas('products.inventory', function($query) use ($warehouse) {
            $query->where('warehouse_id', $warehouse->id);
        })->get();
        
        // Filter inventory by category if requested
        $categoryFilter = $request->get('category_id');
        $filteredInventory = $warehouse->inventory;
        
        if ($categoryFilter) {
            $filteredInventory = $warehouse->inventory->filter(function($inventory) use ($categoryFilter) {
                return $inventory->product->category_id == $categoryFilter;
            });
        }
        
        return view('warehouses.show', compact('warehouse', 'categories', 'filteredInventory', 'categoryFilter'));
    }

    /**
     * Show the form for editing the specified warehouse.
     */
    public function edit(Warehouse $warehouse)
    {
        return view('warehouses.edit', compact('warehouse'));
    }

    /**
     * Update the specified warehouse in storage.
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
    }

    /**
     * Remove the specified warehouse from storage.
     */
    public function destroy(Warehouse $warehouse)
    {
        $warehouse->delete();

        return redirect()->route('warehouses.index')
            ->with('success', 'Kho hàng đã được xóa thành công!');
    }
}
