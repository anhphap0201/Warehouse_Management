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
        return $this->hasMany(StoreInventory::class);    }

    /**
     * Get the notifications for this store.
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get pending notifications for this store.
     */
    public function pendingNotifications(): HasMany
    {
        return $this->hasMany(Notification::class)->where('status', 'pending');
    }

    /**
     * Check if store is active
     */
    public function isActive(): bool
    {
        return $this->status;
    }
}
