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
    ];    /**
     * Lấy các bản ghi tồn kho cửa hàng.
     */
    public function inventory(): HasMany
    {
        return $this->hasMany(StoreInventory::class);
    }

    /**
     * Lấy các thông báo cho cửa hàng này.
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }    /**
     * Lấy các thông báo đang chờ cho cửa hàng này.
     */
    public function pendingNotifications(): HasMany
    {
        return $this->hasMany(Notification::class)->where('status', 'pending');
    }

    /**
     * Kiểm tra xem cửa hàng có đang hoạt động không
     */
    public function isActive(): bool
    {
        return $this->status;
    }
}
