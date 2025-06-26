<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{    /**
     * Hiển thị danh sách các cửa hàng.
     */
    public function index()
    {
        $stores = Store::latest()->paginate(15);
        return view('stores.index', compact('stores'));
    }

    /**
     * Hiển thị form tạo cửa hàng mới.
     */
    public function create()
    {
        return view('stores.create');
    }

    /**
     * Lưu cửa hàng mới được tạo vào storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
        ]);

        Store::create($request->only(['name', 'location']));

        return redirect()->route('stores.index')
            ->with('success', 'Cửa hàng đã được tạo thành công!');
    }    /**
     * Hiển thị cửa hàng cụ thể.
     */
    public function show(Store $store)
    {
        $store->load('inventory');
        return view('stores.show', compact('store'));
    }

    /**
     * Hiển thị form chỉnh sửa cửa hàng cụ thể.
     */
    public function edit(Store $store)
    {
        return view('stores.edit', compact('store'));
    }

    /**
     * Cập nhật cửa hàng cụ thể trong storage.
     */
    public function update(Request $request, Store $store)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
        ]);

        $store->update($request->only(['name', 'location']));

        return redirect()->route('stores.index')
            ->with('success', 'Cửa hàng đã được cập nhật thành công!');
    }    /**
     * Xóa cửa hàng cụ thể khỏi storage.
     */
    public function destroy(Store $store)
    {
        $store->delete();

        return redirect()->route('stores.index')
            ->with('success', 'Cửa hàng đã được xóa thành công!');
    }

    /**
     * Hiển thị form nhận hàng từ kho.
     */
    public function showReceiveForm(Store $store)
    {
        return view('stores.receive', compact('store'));
    }

    /**
     * Nhận hàng từ kho.
     */
    public function receiveStock(Request $request, Store $store)
    {
        // Xử lý logic nhận hàng
        return redirect()->route('stores.show', $store)
            ->with('success', 'Đã nhận hàng thành công!');
    }

    /**
     * Hiển thị form trả hàng về kho.
     */
    public function showReturnForm(Store $store)
    {
        return view('stores.return', compact('store'));
    }

    /**
     * Trả hàng về kho.
     */
    public function returnStock(Request $request, Store $store)
    {
        // Xử lý logic trả hàng
        return redirect()->route('stores.show', $store)
            ->with('success', 'Đã trả hàng về kho thành công!');
    }
}
