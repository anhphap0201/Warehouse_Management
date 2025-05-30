<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'phone',
        'manager',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * Get the store inventory records.
     */
    public function inventory(): HasMany
    {
        return $this->hasMany(StoreInventory::class);
    }

    /**
     * Get the stock movements for the store.
     */
    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    /**
     * Check if store is active
     */
    public function isActive(): bool
    {
        return $this->status;
    }
}
