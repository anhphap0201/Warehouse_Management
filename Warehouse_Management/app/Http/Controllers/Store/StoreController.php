<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    /**
     * Display a listing of the stores.
     */
    public function index()
    {
        $stores = Store::latest()->get();
        return view('stores.index', compact('stores'));
    }

    /**
     * Show the form for creating a new store.
     */
    public function create()
    {
        return view('stores.create');
    }

    /**
     * Store a newly created store in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'manager' => 'nullable|string|max:255',
            'status' => 'boolean',
        ]);

        Store::create($request->all());

        return redirect()->route('stores.index')
            ->with('success', 'Cửa hàng đã được tạo thành công!');
    }

    /**
     * Display the specified store.
     */
    public function show(Store $store)
    {
        $store->load('inventory');
        return view('stores.show', compact('store'));
    }

    /**
     * Show the form for editing the specified store.
     */
    public function edit(Store $store)
    {
        return view('stores.edit', compact('store'));
    }

    /**
     * Update the specified store in storage.
     */
    public function update(Request $request, Store $store)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'manager' => 'nullable|string|max:255',
            'status' => 'boolean',
        ]);

        $store->update($request->all());

        return redirect()->route('stores.index')
            ->with('success', 'Cửa hàng đã được cập nhật thành công!');
    }

    /**
     * Remove the specified store from storage.
     */
    public function destroy(Store $store)
    {
        $store->delete();

        return redirect()->route('stores.index')
            ->with('success', 'Cửa hàng đã được xóa thành công!');
    }

    /**
     * Show the form for receiving stock from warehouse.
     */
    public function showReceiveForm(Store $store)
    {
        return view('stores.receive', compact('store'));
    }

    /**
     * Receive stock from warehouse.
     */
    public function receiveStock(Request $request, Store $store)
    {
        // Logic for receiving stock
        return redirect()->route('stores.show', $store)
            ->with('success', 'Đã nhận hàng thành công!');
    }

    /**
     * Show the form for returning stock to warehouse.
     */
    public function showReturnForm(Store $store)
    {
        return view('stores.return', compact('store'));
    }

    /**
     * Return stock to warehouse.
     */
    public function returnStock(Request $request, Store $store)
    {
        // Logic for returning stock
        return redirect()->route('stores.show', $store)
            ->with('success', 'Đã trả hàng về kho thành công!');
    }
}
