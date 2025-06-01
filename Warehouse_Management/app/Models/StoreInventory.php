<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreInventory extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'store_inventories';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'store_id',
        'product_id',
        'quantity',
        'min_stock',
        'max_stock',
    ];

    /**
     * Get the store that owns the inventory.
     */
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * Get the product that owns the inventory.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Check if the stock is low
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
