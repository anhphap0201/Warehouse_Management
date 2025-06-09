<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreInventory extends Model
{
    use HasFactory;

    /**
     * Bảng được liên kết với model.
     */
    protected $table = 'store_inventories';

    /**
     * Các thuộc tính có thể gán hàng loạt.
     */
    protected $fillable = [
        'store_id',
        'product_id',
        'quantity',
        'min_stock',
        'max_stock',
    ];

    /**
     * Lấy cửa hàng sở hữu tồn kho này.
     */
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * Lấy sản phẩm sở hữu tồn kho này.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Kiểm tra xem tồn kho có thấp không
     */
    public function isLowStock(): bool
    {
        return $this->quantity <= $this->min_stock;
    }

    /**
     * Check if the stock is overstocked
     */
    public function isOverstocked(): bool
    {
        return $this->quantity >= $this->max_stock;
    }
}
