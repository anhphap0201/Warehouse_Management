<?php

namespace App\Services;

use App\Models\Warehouse;
use App\Models\Inventory;
use Illuminate\Database\Eloquent\Collection;

/**
 * WarehouseService - Handles all warehouse-related business logic
 * 
 * This service encapsulates warehouse management operations
 * following the Single Responsibility Principle
 */
class WarehouseService
{
    /**
     * Create a new warehouse
     *
     * @param array $warehouseData
     * @return Warehouse
     */
    public function createWarehouse(array $warehouseData): Warehouse
    {
        return Warehouse::create($warehouseData);
    }

    /**
     * Get a warehouse by ID
     *
     * @param int $warehouseId
     * @return Warehouse|null
     */
    public function getWarehouse(int $warehouseId): ?Warehouse
    {
        return Warehouse::find($warehouseId);
    }

    /**
     * Get all warehouses
     *
     * @return Collection
     */
    public function getAllWarehouses(): Collection
    {
        return Warehouse::all();
    }

    /**
     * Update a warehouse
     *
     * @param int $warehouseId
     * @param array $data
     * @return bool
     */
    public function updateWarehouse(int $warehouseId, array $data): bool
    {
        $warehouse = Warehouse::find($warehouseId);
        if ($warehouse) {
            return $warehouse->update($data);
        }
        return false;
    }

    /**
     * Delete a warehouse
     *
     * @param int $warehouseId
     * @return bool
     */
    public function deleteWarehouse(int $warehouseId): bool
    {
        $warehouse = Warehouse::find($warehouseId);
        if ($warehouse) {
            return $warehouse->delete();
        }
        return false;
    }

    /**
     * Get all products in a specific warehouse
     *
     * @param int $warehouseId
     * @return Collection
     */
    public function getWarehouseProducts(int $warehouseId): Collection
    {
        $warehouse = Warehouse::find($warehouseId);
        if ($warehouse) {
            return $warehouse->getProducts();
        }
        return collect();
    }

    /**
     * Get inventory for a specific warehouse
     *
     * @param int $warehouseId
     * @return Collection
     */
    public function getWarehouseInventory(int $warehouseId): Collection
    {
        return Inventory::where('warehouse_id', $warehouseId)
                       ->with(['product', 'warehouse'])
                       ->get();
    }

    /**
     * Search warehouses by name or location
     *
     * @param string $query
     * @return Collection
     */
    public function searchWarehouses(string $query): Collection
    {
        return Warehouse::where('name', 'like', "%{$query}%")
                        ->orWhere('location', 'like', "%{$query}%")
                        ->get();
    }
}
