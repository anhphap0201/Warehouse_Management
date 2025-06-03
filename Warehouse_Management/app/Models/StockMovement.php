<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'product_id',
        'warehouse_id',
        'type',
        'quantity',
        'date',
        'reference_note',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'date' => 'datetime',
    ];

    /**
     * Get the product that owns the stock movement.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the warehouse that owns the stock movement.
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * Scope a query to only include IN movements.
     */
    public function scopeIn($query)
    {
        return $query->where('type', 'IN');
    }

    /**
     * Scope a query to only include OUT movements.
     */
    public function scopeOut($query)
    {
        return $query->where('type', 'OUT');
    }

    /**
     * Get the formatted movement type.
     */
    public function getFormattedTypeAttribute(): string
    {
        return $this->type === 'IN' ? 'Nhập kho' : 'Xuất kho';
    }
}
