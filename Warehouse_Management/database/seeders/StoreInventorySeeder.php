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
        // Xóa dữ liệu tồn kho cửa hàng hiện có
        StoreInventory::truncate();

        // Lấy tất cả sản phẩm và cửa hàng
        $products = Product::all();
        $stores = Store::where('status', true)->get(); // Chỉ các cửa hàng hoạt động

        if ($products->isEmpty() || $stores->isEmpty()) {
            $this->command->warn('No products or active stores found. Please run ProductSeeder and StoreSeeder first.');
            return;
        }

        $storeInventoryData = [];

        foreach ($stores as $store) {
            // Mỗi cửa hàng sẽ có lựa chọn sản phẩm ngẫu nhiên (30-70% tổng số sản phẩm)
            $productCount = $products->count();
            $storeProductCount = rand(
                (int)($productCount * 0.3), 
                (int)($productCount * 0.7)
            );
            
            $storeProducts = $products->random($storeProductCount);
            
            foreach ($storeProducts as $product) {
                // Số lượng ngẫu nhiên cho tồn kho cửa hàng
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

        // Chèn dữ liệu tồn kho cửa hàng theo từng phần
        $chunks = array_chunk($storeInventoryData, 100);
        foreach ($chunks as $chunk) {
            StoreInventory::insert($chunk);
        }

        $totalStoreInventory = StoreInventory::count();
        $this->command->info("Created {$totalStoreInventory} store inventory records");
        
        // Hiển thị thống kê tồn kho theo cửa hàng
        foreach ($stores->take(5) as $store) { // Hiển thị 5 cửa hàng đầu tiên
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
