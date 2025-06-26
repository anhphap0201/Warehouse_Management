<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Warehouse Model - Matches Class Diagram Requirements
 * 
 * Fields according to class diagram:
 * - id : bigint (primary key, auto-increment)
 * - name : string (required)
 * - location : string (required)
 * - created_at : timestamp (auto-managed by Laravel)
 * - updated_at : timestamp (auto-managed by Laravel)
 */
class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
    ];

    protected $casts = [
        'id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public static function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
        ];
    }

    public static function updateRules($id)
    {
        return [
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
        ];
    }


    public function inventory(): HasMany
    {
        return $this->hasMany(Inventory::class);
    }


    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }


    public function getProducts()
    {
        return $this->inventory()->with('product')->get()->pluck('product');
    }

    public function getInventories()
    {
        return $this->inventory;
    }
}
