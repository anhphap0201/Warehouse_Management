<?php

namespace App\Services;

use App\Models\Store;
use App\Models\StoreInventory;
use App\Models\Notification;
use Illuminate\Database\Eloquent\Collection;

/**
 * StoreService - Handles all store-related business logic
 * 
 * This service encapsulates store management operations
 * following the Single Responsibility Principle
 */
class StoreService
{
    public function __construct(
        private InventoryService $inventoryService
    ) {}

    /**
     * Create a new store
     *
     * @param array $storeData
     * @return Store
     */
    public function createStore(array $storeData): Store
    {
        return Store::create($storeData);
    }

    /**
     * Get a store by ID
     *
     * @param int $storeId
     * @return Store|null
     */
    public function getStore(int $storeId): ?Store
    {
        return Store::find($storeId);
    }

    /**
     * Get all stores
     *
     * @return Collection
     */
    public function getAllStores(): Collection
    {
        return Store::all();
    }

    /**
     * Update a store
     *
     * @param int $storeId
     * @param array $data
     * @return bool
     */
    public function updateStore(int $storeId, array $data): bool
    {
        $store = Store::find($storeId);
        if ($store) {
            return $store->update($data);
        }
        return false;
    }

    /**
     * Delete a store
     *
     * @param int $storeId
     * @return bool
     */
    public function deleteStore(int $storeId): bool
    {
        $store = Store::find($storeId);
        if ($store) {
            return $store->delete();
        }
        return false;
    }

    /**
     * Get store inventory
     *
     * @param int $storeId
     * @return Collection
     */
    public function getStoreInventory(int $storeId): Collection
    {
        return StoreInventory::where('store_id', $storeId)
                            ->with(['product'])
                            ->get();
    }

    /**
     * Get pending notifications for a store
     *
     * @param int $storeId
     * @return Collection
     */
    public function getPendingNotifications(int $storeId): Collection
    {
        return Notification::where([
            'store_id' => $storeId,
            'status' => 'pending'
        ])->get();
    }

    /**
     * Get low stock items in store
     *
     * @param int $storeId
     * @return Collection
     */
    public function getLowStockItems(int $storeId): Collection
    {
        return StoreInventory::where('store_id', $storeId)
                            ->whereRaw('quantity <= min_stock')
                            ->with(['product'])
                            ->get();
    }

    /**
     * Get overstocked items in store
     *
     * @param int $storeId
     * @return Collection
     */
    public function getOverstockedItems(int $storeId): Collection
    {
        return StoreInventory::where('store_id', $storeId)
                            ->whereRaw('quantity >= max_stock')
                            ->with(['product'])
                            ->get();
    }

    /**
     * Receive stock from warehouse (uses InventoryService)
     *
     * @param int $storeId
     * @param int $productId
     * @param int $warehouseId
     * @param int $quantity
     * @return bool
     */
    public function receiveStockFromWarehouse(int $storeId, int $productId, int $warehouseId, int $quantity): bool
    {
        return $this->inventoryService->receiveStockFromWarehouse($storeId, $productId, $warehouseId, $quantity);
    }

    /**
     * Return stock to warehouse (uses InventoryService)
     *
     * @param int $storeId
     * @param int $productId
     * @param int $warehouseId
     * @param int $quantity
     * @return bool
     */
    public function returnStockToWarehouse(int $storeId, int $productId, int $warehouseId, int $quantity): bool
    {
        return $this->inventoryService->returnStockToWarehouse($storeId, $productId, $warehouseId, $quantity);
    }

    /**
     * Search stores by name or location
     *
     * @param string $query
     * @return Collection
     */
    public function searchStores(string $query): Collection
    {
        return Store::where('name', 'like', "%{$query}%")
                   ->orWhere('location', 'like', "%{$query}%")
                   ->get();
    }
}
