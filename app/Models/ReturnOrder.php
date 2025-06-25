<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'warehouse_id',
        'supplier_id',
        'supplier_name',
        'supplier_phone',
        'supplier_address',
        'invoice_number',
        'total_amount',
        'status',
        'notes',
        'return_reason',
        'processed_at'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    /**
     * Quan hệ với kho hàng
     */
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * Quan hệ với nhà cung cấp
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Quan hệ với các sản phẩm trả
     */
    public function items()
    {
        return $this->hasMany(ReturnOrderItem::class);
    }

    /**
     * Scope cho đơn trả đang chờ xử lý
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope cho đơn trả đã xử lý
     */
    public function scopeProcessed($query)
    {
        return $query->where('status', 'processed');
    }

    /**
     * Scope cho đơn trả đã hủy
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }
}
