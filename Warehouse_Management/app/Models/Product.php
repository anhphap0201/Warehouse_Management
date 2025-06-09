<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
}
