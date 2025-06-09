<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SupplierController extends Controller
{
    /**
     * Hiển thị danh sách tài nguyên.
     */
    public function index(): View
    {
        $suppliers = Supplier::latest()->paginate(10);
        return view('suppliers.index', compact('suppliers'));
    }

    /**
     * Hiển thị form để tạo tài nguyên mới.
     */
    public function create(): View
    {
        return view('suppliers.create');
    }

    /**
     * Lưu tài nguyên mới được tạo vào storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'tax_number' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive'
        ]);

        Supplier::create($validated);

        return redirect()->route('suppliers.index')
            ->with('success', 'Nhà cung cấp đã được tạo thành công.');
    }

    /**
     * Hiển thị tài nguyên được chỉ định.
     */
    public function show(Supplier $supplier): View
    {
        $supplier->load('purchaseOrders.warehouse');
        return view('suppliers.show', compact('supplier'));
    }

    /**
     * Hiển thị form để chỉnh sửa tài nguyên được chỉ định.
     */
    public function edit(Supplier $supplier): View
    {
        return view('suppliers.edit', compact('supplier'));
    }

    /**
     * Cập nhật tài nguyên được chỉ định trong storage.
     */
    public function update(Request $request, Supplier $supplier): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'tax_number' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive'
        ]);

        $supplier->update($validated);

        return redirect()->route('suppliers.index')
            ->with('success', 'Nhà cung cấp đã được cập nhật thành công.');
    }

    /**
     * Xóa tài nguyên được chỉ định khỏi storage.
     */
    public function destroy(Supplier $supplier): RedirectResponse
    {
        // Kiểm tra xem nhà cung cấp có đơn hàng mua không
        if ($supplier->purchaseOrders()->count() > 0) {
            return redirect()->route('suppliers.index')
                ->with('error', 'Không thể xóa nhà cung cấp có đơn hàng mua.');
        }

        $supplier->delete();

        return redirect()->route('suppliers.index')
            ->with('success', 'Nhà cung cấp đã được xóa thành công.');
    }

    /**
     * Tìm kiếm nhà cung cấp (API endpoint)
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        $suppliers = Supplier::active()
            ->search($query)
            ->limit(10)
            ->get(['id', 'name', 'contact_person', 'phone']);

        return response()->json($suppliers);
    }
}
