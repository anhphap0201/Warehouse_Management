<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

/**
 * ProductService - Handles all product-related business logic
 * 
 * This service encapsulates product management operations
 * following the Single Responsibility Principle
 */
class ProductService
{
    /**
     * Create a new product
     *
     * @param array $productData
     * @return Product
     */
    public function createProduct(array $productData): Product
    {
        return Product::create($productData);
    }

    /**
     * Get a product by ID
     *
     * @param int $productId
     * @return Product|null
     */
    public function getProduct(int $productId): ?Product
    {
        return Product::find($productId);
    }

    /**
     * Get all products
     *
     * @return Collection
     */
    public function getAllProducts(): Collection
    {
        return Product::all();
    }

    /**
     * Update a product
     *
     * @param int $productId
     * @param array $data
     * @return bool
     */
    public function updateProduct(int $productId, array $data): bool
    {
        $product = Product::find($productId);
        if ($product) {
            return $product->update($data);
        }
        return false;
    }

    /**
     * Delete a product
     *
     * @param int $productId
     * @return bool
     */
    public function deleteProduct(int $productId): bool
    {
        $product = Product::find($productId);
        if ($product) {
            return $product->delete();
        }
        return false;
    }

    /**
     * Search products by name or SKU
     *
     * @param string $query
     * @return Collection
     */
    public function searchProducts(string $query): Collection
    {
        return Product::where('name', 'like', "%{$query}%")
                     ->orWhere('sku', 'like', "%{$query}%")
                     ->get();
    }

    /**
     * Get products by category
     *
     * @param int $categoryId
     * @return Collection
     */
    public function getProductsByCategory(int $categoryId): Collection
    {
        return Product::where('category_id', $categoryId)->get();
    }
}
