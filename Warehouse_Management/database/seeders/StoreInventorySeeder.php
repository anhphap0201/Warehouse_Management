<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StoreInventory;
use App\Models\Product;
use App\Models\Store;

class StoreInventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing store inventory data
        StoreInventory::truncate();

        // Get all products and stores
        $products = Product::all();
        $stores = Store::where('status', true)->get(); // Only active stores

        if ($products->isEmpty() || $stores->isEmpty()) {
            $this->command->warn('No products or active stores found. Please run ProductSeeder and StoreSeeder first.');
            return;
        }

        $storeInventoryData = [];

        foreach ($stores as $store) {
            // Each store will have a random selection of products (30-70% of all products)
            $productCount = $products->count();
            $storeProductCount = rand(
                (int)($productCount * 0.3), 
                (int)($productCount * 0.7)
            );
            
            $storeProducts = $products->random($storeProductCount);
            
            foreach ($storeProducts as $product) {
                // Random quantities for store inventory
                $quantity = rand(0, 200);
                $minStock = rand(5, 20);
                $maxStock = rand($minStock + 50, $minStock + 300);
                
                $storeInventoryData[] = [
                    'store_id' => $store->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'min_stock' => $minStock,
                    'max_stock' => $maxStock,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Insert store inventory data in chunks
        $chunks = array_chunk($storeInventoryData, 100);
        foreach ($chunks as $chunk) {
            StoreInventory::insert($chunk);
        }

        $totalStoreInventory = StoreInventory::count();
        $this->command->info("Created {$totalStoreInventory} store inventory records");
        
        // Show inventory statistics by store
        foreach ($stores->take(5) as $store) { // Show first 5 stores
            $storeInventoryCount = $store->inventory->count();
            $totalQuantity = $store->inventory->sum('quantity');
            $lowStockCount = $store->inventory->filter(function($inv) {
                return $inv->isLowStock();
            })->count();
            
            $this->command->info("- {$store->name}: {$storeInventoryCount} products, total: {$totalQuantity}, low stock: {$lowStockCount}");
        }
        
        if ($stores->count() > 5) {
            $remaining = $stores->count() - 5;
            $this->command->info("... and {$remaining} more stores");
        }
    }
}
