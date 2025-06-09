<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Hiển thị danh sách các sản phẩm.
     */
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(15);
        return view('products.index', compact('products'));
    }

    /**
     * Hiển thị form tạo sản phẩm mới.
     */
    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    /**
     * Lưu sản phẩm mới được tạo vào storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:products',
            'category_id' => 'nullable|exists:categories,id',
            'unit' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);

        Product::create($request->all());

        return redirect()->route('products.index')
            ->with('success', 'Sản phẩm đã được tạo thành công!');
    }

    /**
     * Hiển thị sản phẩm cụ thể.
     */
    public function show(Product $product)
    {
        $product->load(['category', 'inventory.warehouse']);
        return view('products.show', compact('product'));
    }

    /**
     * Hiển thị form chỉnh sửa sản phẩm cụ thể.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Cập nhật sản phẩm cụ thể trong storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:products,sku,' . $product->id,
            'category_id' => 'nullable|exists:categories,id',
            'unit' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);

        $product->update($request->all());

        return redirect()->route('products.index')
            ->with('success', 'Sản phẩm đã được cập nhật thành công!');
    }

    /**
     * Xóa sản phẩm cụ thể khỏi storage.
     */
    public function destroy(Product $product)
    {
        // Kiểm tra xem sản phẩm có tồn kho không
        if ($product->inventory()->exists()) {
            return redirect()->route('products.index')
                ->with('error', 'Không thể xóa sản phẩm vì còn tồn kho!');
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Sản phẩm đã được xóa thành công!');
    }

    /**
     * Lấy thông tin chi tiết sản phẩm cho API
     */
    public function getProduct($id)
    {
        $product = Product::find($id);
        
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'sku' => $product->sku,
            'unit' => $product->unit
        ]);
    }
}
