<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use App\Services\WarehouseService;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * Example Controller showing how to use Services
 * instead of putting business logic in User model
 */
class ExampleRefactoredController extends Controller
{
    public function __construct(
        private ProductService $productService,
        private WarehouseService $warehouseService,
        private InventoryService $inventoryService
    ) {}

    /**
     * Create a new product (previously in User model)
     */
    public function createProduct(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products',
            'category_id' => 'required|exists:categories,id',
            'unit' => 'required|string|max:50',
            'description' => 'nullable|string',
        ]);

        // Check if user has permission
        if (!auth()->user()->canManageProducts()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $product = $this->productService->createProduct($validated);

        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product
        ], 201);
    }

    /**
     * Get product details (previously in User model)
     */
    public function getProduct(int $productId): JsonResponse
    {
        $product = $this->productService->getProduct($productId);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        return response()->json(['product' => $product]);
    }

    /**
     * Update product (previously in User model)
     */
    public function updateProduct(Request $request, int $productId): JsonResponse
    {
        // Check if user has permission
        if (!auth()->user()->canManageProducts()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'sku' => 'sometimes|string|unique:products,sku,' . $productId,
            'category_id' => 'sometimes|exists:categories,id',
            'unit' => 'sometimes|string|max:50',
            'description' => 'nullable|string',
        ]);

        $success = $this->productService->updateProduct($productId, $validated);

        if (!$success) {
            return response()->json(['error' => 'Product not found or update failed'], 404);
        }

        return response()->json(['message' => 'Product updated successfully']);
    }

    /**
     * Delete product (previously in User model)
     */
    public function deleteProduct(int $productId): JsonResponse
    {
        // Check if user has permission
        if (!auth()->user()->canManageProducts()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $success = $this->productService->deleteProduct($productId);

        if (!$success) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        return response()->json(['message' => 'Product deleted successfully']);
    }

    /**
     * Get all warehouses (previously in User model)
     */
    public function getWarehouses(): JsonResponse
    {
        // Check if user has permission
        if (!auth()->user()->canManageWarehouses()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $warehouses = $this->warehouseService->getAllWarehouses();

        return response()->json(['warehouses' => $warehouses]);
    }

    /**
     * Transfer product to store (previously in User model)
     */
    public function transferToStore(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Check if user has permission
        if (!auth()->user()->canManageInventory()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $success = $this->inventoryService->transferToStore(
            $validated['product_id'],
            $validated['warehouse_id'],
            $validated['quantity']
        );

        if (!$success) {
            return response()->json([
                'error' => 'Transfer failed. Insufficient inventory or invalid data.'
            ], 400);
        }

        return response()->json(['message' => 'Transfer completed successfully']);
    }
}
