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
        // Xóa dữ liệu tồn kho hiện có
        Inventory::truncate();

        // Lấy tất cả sản phẩm và kho hàng
        $products = Product::all();
        $warehouses = Warehouse::all();

        if ($products->isEmpty() || $warehouses->isEmpty()) {
            $this->command->warn('No products or warehouses found. Please run ProductSeeder and WarehouseSeeder first.');
            return;
        }

        // Tạo các bản ghi tồn kho
        $inventoryData = [];

        foreach ($warehouses as $warehouse) {
            // Mỗi kho hàng sẽ có lựa chọn sản phẩm ngẫu nhiên
            $warehouseProducts = $products->random(rand(5, min(15, $products->count())));
            
            foreach ($warehouseProducts as $product) {
                // Số lượng ngẫu nhiên từ 10 đến 1000
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

        // Chèn dữ liệu tồn kho theo từng phần để cải thiện hiệu suất
        $chunks = array_chunk($inventoryData, 100);
        foreach ($chunks as $chunk) {
            Inventory::insert($chunk);
        }

        $totalInventory = Inventory::count();
        $this->command->info("Created {$totalInventory} inventory records");
        
        // Hiển thị tồn kho theo kho hàng
        foreach ($warehouses as $warehouse) {
            $warehouseInventoryCount = $warehouse->inventory->count();
            $totalQuantity = $warehouse->inventory->sum('quantity');
            $this->command->info("- {$warehouse->name}: {$warehouseInventoryCount} products, total quantity: {$totalQuantity}");
        }
    }
}
