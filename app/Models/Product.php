<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Product Model - Matches Class Diagram Requirements
 * 
 * Fields according to class diagram:
 * - id : bigint (primary key, auto-increment)
 * - name : string (required)
 * - sku : string (required, unique)
 * - category_id : bigint (required, foreign key to categories table)
 * - unit : string (required)
 * - description : text (nullable)
 * - created_at : timestamp (auto-managed by Laravel)
 * - updated_at : timestamp (auto-managed by Laravel)
 */

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sku',
        'category_id',
        'unit',
        'description',
    ];

    protected $casts = [
        'id' => 'integer',
        'category_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Lấy danh mục sở hữu sản phẩm này.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Lấy các bản ghi tồn kho cho sản phẩm.
     */
    public function inventory(): HasMany
    {
        return $this->hasMany(Inventory::class);
    }

    /**
     * Lấy các bản ghi tồn kho cửa hàng cho sản phẩm.
     */
    public function storeInventories(): HasMany
    {
        return $this->hasMany(StoreInventory::class);
    }

    /**
     * Lấy tổng số lượng trên tất cả các kho
     */
    public function getTotalQuantityAttribute(): int
    {
        return $this->inventory->sum('quantity');
    }

    /**
     * Lấy các biến động tồn kho cho sản phẩm.
     */
    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    /**
     * Validation rules for Product model
     */
    public static function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:255|unique:products,sku',
            'category_id' => 'required|exists:categories,id',
            'unit' => 'required|string|max:255',
            'description' => 'nullable|string',
        ];
    }

    /**
     * Validation rules for updating Product
     */
    public static function updateRules($id)
    {
        return [
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:255|unique:products,sku,' . $id,
            'category_id' => 'required|exists:categories,id',
            'unit' => 'required|string|max:255',
            'description' => 'nullable|string',
        ];
    }
}
