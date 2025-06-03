<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StockMovement;
use App\Models\Inventory;
use Carbon\Carbon;

class StockMovementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing stock movement data
        StockMovement::truncate();

        // Get all inventory records
        $inventories = Inventory::with(['product', 'warehouse'])->get();

        if ($inventories->isEmpty()) {
            $this->command->warn('No inventory records found. Please run InventorySeeder first.');
            return;
        }

        $stockMovements = [];
        $movementTypes = ['IN', 'OUT'];
        $notes = [
            'IN' => [
                'Nhập hàng từ nhà cung cấp',
                'Chuyển kho từ chi nhánh khác',
                'Nhập hàng bổ sung',
                'Nhập hàng đầu kỳ',
                'Điều chỉnh tăng tồn kho'
            ],
            'OUT' => [
                'Xuất bán cho khách hàng',
                'Chuyển kho sang chi nhánh khác',
                'Xuất hàng hỏng',
                'Kiểm kế giảm tồn',
                'Xuất sử dụng nội bộ'
            ]
        ];

        foreach ($inventories as $inventory) {
            // Create 3-8 stock movements for each inventory item
            $movementCount = rand(3, 8);
            
            for ($i = 0; $i < $movementCount; $i++) {
                $type = $movementTypes[array_rand($movementTypes)];
                $quantity = rand(1, min(100, $inventory->quantity));
                
                // Generate random date within last 6 months
                $date = Carbon::now()->subDays(rand(1, 180));
                
                $stockMovements[] = [
                    'product_id' => $inventory->product_id,
                    'warehouse_id' => $inventory->warehouse_id,
                    'type' => $type,
                    'quantity' => $quantity,
                    'date' => $date,
                    'reference_note' => $notes[$type][array_rand($notes[$type])],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Insert stock movements in chunks
        $chunks = array_chunk($stockMovements, 200);
        foreach ($chunks as $chunk) {
            StockMovement::insert($chunk);
        }

        $totalMovements = StockMovement::count();
        $inMovements = StockMovement::where('type', 'IN')->count();
        $outMovements = StockMovement::where('type', 'OUT')->count();
        
        $this->command->info("Created {$totalMovements} stock movements:");
        $this->command->info("- IN movements: {$inMovements}");
        $this->command->info("- OUT movements: {$outMovements}");
    }
}
