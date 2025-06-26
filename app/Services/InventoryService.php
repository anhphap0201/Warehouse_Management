<?php

namespace App\Services;

use App\Models\Inventory;
use App\Models\StoreInventory;
use App\Models\StockMovement;
use Illuminate\Database\Eloquent\Collection;


class InventoryService
{

    public function createOrUpdateInventory(int $productId, int $warehouseId, int $quantity): Inventory
    {
        return Inventory::updateOrCreate(
            [
                'product_id' => $productId,
                'warehouse_id' => $warehouseId
            ],
            ['quantity' => $quantity]
        );
    }

    public function getInventory(int $productId, int $warehouseId): ?Inventory
    {
        return Inventory::where([
            'product_id' => $productId,
            'warehouse_id' => $warehouseId
        ])->first();
    }


    public function getAllInventory(): Collection
    {
        return Inventory::with(['product', 'warehouse'])->get();
    }

    public function updateInventoryQuantity(int $productId, int $warehouseId, int $newQuantity): bool
    {
        $inventory = $this->getInventory($productId, $warehouseId);
        if ($inventory) {
            return $inventory->update(['quantity' => $newQuantity]);
        }
        return false;
    }

    public function transferToStore(int $productId, int $warehouseId, int $storeId, int $quantity): bool
    {
        $inventory = $this->getInventory($productId, $warehouseId);

        if (!$inventory || $inventory->quantity < $quantity) {
            return false;
        }


        \DB::beginTransaction();

        try {
            // Reduce warehouse inventory
            $inventory->decrement('quantity', $quantity);

            // Add to store inventory
            StoreInventory::updateOrCreate(
                [
                    'product_id' => $productId,
                    'store_id' => $storeId
                ],
                ['quantity' => \DB::raw("quantity + {$quantity}")]
            );

            StockMovement::create([
                'product_id' => $productId,
                'warehouse_id' => $warehouseId,
                'store_id' => $storeId,
                'quantity' => $quantity,
                'type' => 'transfer_to_store',
                'notes' => "Transferred {$quantity} units from warehouse to store"
            ]);

            \DB::commit();
            return true;

        } catch (\Exception $e) {
            \DB::rollback();
            return false;
        }
    }

    public function hassufficientStock(int $productId, int $warehouseId, int $requiredQuantity): bool
    {
        $inventory = $this->getInventory($productId, $warehouseId);
        return $inventory && $inventory->quantity >= $requiredQuantity;
    }

    public function getLowStockItems(int $threshold = 10): Collection
    {
        return Inventory::where('quantity', '<', $threshold)
                       ->with(['product', 'warehouse'])
                       ->get();
    }


    public function getInventoryByProduct(int $productId): Collection
    {
        return Inventory::where('product_id', $productId)
                       ->with(['warehouse'])
                       ->get();
    }


    public function receiveStockFromWarehouse(int $storeId, int $productId, int $warehouseId, int $quantity): bool
    {
        $inventory = $this->getInventory($productId, $warehouseId);

        if (!$inventory || $inventory->quantity < $quantity) {
            return false;
        }

        \DB::beginTransaction();

        try {
            $inventory->decrement('quantity', $quantity);

            StoreInventory::updateOrCreate(
                [
                    'product_id' => $productId,
                    'store_id' => $storeId
                ],
                ['quantity' => \DB::raw("quantity + {$quantity}")]
            );

            StockMovement::create([
                'product_id' => $productId,
                'warehouse_id' => $warehouseId,
                'store_id' => $storeId,
                'quantity' => $quantity,
                'type' => 'transfer_to_store',
                'notes' => "Stock received by store from warehouse"
            ]);

            \DB::commit();
            return true;

        } catch (\Exception $e) {
            \DB::rollback();
            return false;
        }
    }

    public function returnStockToWarehouse(int $storeId, int $productId, int $warehouseId, int $quantity): bool
    {
        $storeInventory = StoreInventory::where([
            'product_id' => $productId,
            'store_id' => $storeId
        ])->first();

        if (!$storeInventory || $storeInventory->quantity < $quantity) {
            return false;
        }

        \DB::beginTransaction();

        try {
            // Reduce store inventory
            $storeInventory->decrement('quantity', $quantity);

            // Add to warehouse inventory
            $this->createOrUpdateInventory($productId, $warehouseId, $quantity);

            // Record stock movement
            StockMovement::create([
                'product_id' => $productId,
                'warehouse_id' => $warehouseId,
                'store_id' => $storeId,
                'quantity' => $quantity,
                'type' => 'return_to_warehouse',
                'notes' => "Stock returned by store to warehouse"
            ]);

            \DB::commit();
            return true;

        } catch (\Exception $e) {
            \DB::rollback();
            return false;
        }
    }
}
