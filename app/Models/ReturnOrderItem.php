<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'return_order_id',
        'product_id',
        'quantity',
        'unit_price',
        'total_price',
        'return_reason'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    /**
     * Quan hệ với đơn trả hàng
     */
    public function returnOrder()
    {
        return $this->belongsTo(ReturnOrder::class);
    }

    /**
     * Quan hệ với sản phẩm
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
