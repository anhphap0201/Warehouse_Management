<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * StockMovement Model - Matches Class Diagram Requirements
 * 
 * Fields according to class diagram:
 * - id : bigint (primary key, auto-increment)
 * - product_id : bigint (foreign key to products table)
 * - warehouse_id : bigint (foreign key to warehouses table)
 * - type : enum [IN, OUT]
 * - quantity : int
 * - date : timestamp
 * - reference_note : string
 * - created_at : timestamp (auto-managed by Laravel)
 * - updated_at : timestamp (auto-managed by Laravel)
 */
class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'warehouse_id',
        'type',
        'quantity',
        'date',
        'reference_note',
    ];

    protected $casts = [
        'id' => 'integer',
        'product_id' => 'integer',
        'warehouse_id' => 'integer',
        'quantity' => 'integer',
        'date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Validation rules for StockMovement model
     */
    public static function rules()
    {
        return [
            'product_id' => 'required|exists:products,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'type' => 'required|in:IN,OUT',
            'quantity' => 'required|integer|min:1',
            'date' => 'required|date',
            'reference_note' => 'nullable|string|max:255',
        ];
    }

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
     * Record a stock movement
     */
    public function recordMovement(): void
    {
        $this->save();
        
        // Update inventory accordingly
        $inventory = Inventory::firstOrCreate([
            'product_id' => $this->product_id,
            'warehouse_id' => $this->warehouse_id,
        ], ['quantity' => 0]);

        if ($this->type === 'IN') {
            $inventory->quantity += $this->quantity;
        } else {
            $inventory->quantity -= $this->quantity;
        }
        
        $inventory->save();
    }

    /**
     * Reverse a stock movement
     */
    public function reverseMovement(): void
    {
        $inventory = Inventory::where([
            'product_id' => $this->product_id,
            'warehouse_id' => $this->warehouse_id,
        ])->first();

        if ($inventory) {
            if ($this->type === 'IN') {
                $inventory->quantity -= $this->quantity;
            } else {
                $inventory->quantity += $this->quantity;
            }
            
            $inventory->save();
        }
        
        $this->delete();
    }

    /**
     * Get movements by product
     */
    public static function getMovementsByProduct($productId)
    {
        return self::where('product_id', $productId)->orderBy('date', 'desc')->get();
    }

    /**
     * Get movements by warehouse
     */
    public static function getMovementsByWarehouse($warehouseId)
    {
        return self::where('warehouse_id', $warehouseId)->orderBy('date', 'desc')->get();
    }
}
