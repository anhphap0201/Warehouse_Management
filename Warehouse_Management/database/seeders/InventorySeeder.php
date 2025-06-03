<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Warehouse;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing inventory data
        Inventory::truncate();

        // Get all products and warehouses
        $products = Product::all();
        $warehouses = Warehouse::all();

        if ($products->isEmpty() || $warehouses->isEmpty()) {
            $this->command->warn('No products or warehouses found. Please run ProductSeeder and WarehouseSeeder first.');
            return;
        }

        // Create inventory records
        $inventoryData = [];

        foreach ($warehouses as $warehouse) {
            // Each warehouse will have a random selection of products
            $warehouseProducts = $products->random(rand(5, min(15, $products->count())));
            
            foreach ($warehouseProducts as $product) {
                // Random quantity between 10 and 1000
                $quantity = rand(10, 1000);
                
                $inventoryData[] = [
                    'product_id' => $product->id,
                    'warehouse_id' => $warehouse->id,
                    'quantity' => $quantity,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Insert inventory data in chunks for better performance
        $chunks = array_chunk($inventoryData, 100);
        foreach ($chunks as $chunk) {
            Inventory::insert($chunk);
        }

        $totalInventory = Inventory::count();
        $this->command->info("Created {$totalInventory} inventory records");
        
        // Show inventory by warehouse
        foreach ($warehouses as $warehouse) {
            $warehouseInventoryCount = $warehouse->inventory->count();
            $totalQuantity = $warehouse->inventory->sum('quantity');
            $this->command->info("- {$warehouse->name}: {$warehouseInventoryCount} products, total quantity: {$totalQuantity}");
        }
    }
}
