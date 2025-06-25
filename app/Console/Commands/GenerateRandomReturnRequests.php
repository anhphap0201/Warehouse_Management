<?php

namespace App\Console\Commands;

use App\Models\Store;
use App\Models\Notification;
use App\Models\StoreInventory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GenerateRandomReturnRequests extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'stores:generate-return-requests 
                           {--stores=* : Specific store IDs to generate requests for}
                           {--percentage=30 : Percentage of active stores that should generate requests}
                           {--min-products=1 : Minimum number of products to return}
                           {--max-products=5 : Maximum number of products to return}
                           {--min-quantity=1 : Minimum quantity per product}
                           {--max-quantity=10 : Maximum quantity per product}';

    /**
     * The console command description.
     */
    protected $description = 'Generate random return requests from stores to warehouse';

    /**
     * List of possible return reasons
     */
    private $returnReasons = [
        'Sản phẩm hết hạn sử dụng',
        'Hàng bị hư hỏng trong quá trình vận chuyển',
        'Sản phẩm không phù hợp với khách hàng',
        'Hàng tồn kho quá lâu',
        'Sản phẩm có lỗi từ nhà sản xuất',
        'Yêu cầu đổi trả từ khách hàng',
        'Hàng bị ẩm mốc do bảo quản không tốt',
        'Sản phẩm không bán được',
        'Cần giảm tồn kho',
        'Hàng sắp hết hạn',
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Bắt đầu tạo yêu cầu trả hàng ngẫu nhiên...');
        
        $stores = $this->getEligibleStores();
        
        if ($stores->isEmpty()) {
            $this->warn('❌ Không tìm thấy cửa hàng nào có đủ điều kiện để tạo yêu cầu trả hàng.');
            return Command::SUCCESS;
        }

        $this->info("📋 Tìm thấy {$stores->count()} cửa hàng có thể tạo yêu cầu trả hàng");

        $requestsGenerated = 0;
        $totalStores = $stores->count();

        // Tính toán có bao nhiêu cửa hàng nên tạo yêu cầu
        $percentage = (int) $this->option('percentage');
        $storesToProcess = max(1, (int) round(($percentage / 100) * $totalStores));

        $this->info("🎯 Sẽ tạo yêu cầu cho {$storesToProcess} cửa hàng ({$percentage}%)");

        // Chọn ngẫu nhiên các cửa hàng
        $selectedStores = $stores->random($storesToProcess);

        foreach ($selectedStores as $store) {
            try {
                DB::beginTransaction();
                
                $request = $this->generateReturnRequestForStore($store);
                
                if ($request) {
                    $requestsGenerated++;
                    $this->info("✅ Tạo yêu cầu trả hàng cho cửa hàng: {$store->name} (ID: {$request->id})");
                } else {
                    $this->warn("⚠️  Không thể tạo yêu cầu cho cửa hàng: {$store->name}");
                }
                
                DB::commit();
                
            } catch (\Exception $e) {
                DB::rollBack();
                $this->error("❌ Lỗi khi tạo yêu cầu cho cửa hàng {$store->name}: {$e->getMessage()}");
                Log::error('Generate return request failed', [
                    'store_id' => $store->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }

        $this->info("🎉 Hoàn thành! Đã tạo {$requestsGenerated} yêu cầu trả hàng từ {$storesToProcess} cửa hàng");
        
        return Command::SUCCESS;
    }

    /**
     * Get stores that are eligible for generating return requests
     */
    private function getEligibleStores()
    {
        $storeIds = $this->option('stores');
        
        $query = Store::whereHas('inventory', function ($q) {
                $q->where('quantity', '>', 0);
            })
            ->with(['inventory' => function ($q) {
                $q->where('quantity', '>', 0)->with('product');
            }]);
        
        if (!empty($storeIds)) {
            $query->whereIn('id', $storeIds);
        }
        
        return $query->get();
    }

    /**
     * Generate a return request for a specific store
     */
    private function generateReturnRequestForStore(Store $store)
    {
        $availableInventory = $store->inventory->where('quantity', '>', 0);
        
        if ($availableInventory->isEmpty()) {
            return null;
        }

        $minProducts = (int) $this->option('min-products');
        $maxProducts = min((int) $this->option('max-products'), $availableInventory->count());
        $numProducts = rand($minProducts, $maxProducts);

        // Chọn ngẫu nhiên các sản phẩm để trả
        $selectedInventory = $availableInventory->random($numProducts);
        
        $products = [];
        $totalItems = 0;

        foreach ($selectedInventory as $inventory) {
            $minQty = (int) $this->option('min-quantity');
            $maxQty = min((int) $this->option('max-quantity'), $inventory->quantity);
            
            if ($maxQty < $minQty) {
                continue; // Bỏ qua nếu không đủ số lượng
            }
            
            $quantity = rand($minQty, $maxQty);
            $reason = $this->returnReasons[array_rand($this->returnReasons)];
            
            $products[] = [
                'product_id' => $inventory->product->id,
                'product_name' => $inventory->product->name,
                'product_sku' => $inventory->product->sku,
                'quantity' => $quantity,
                'reason' => $reason,
                'available_quantity' => $inventory->quantity
            ];
            
            $totalItems += $quantity;
        }

        if (empty($products)) {
            return null;
        }

        // Tạo tiêu đề và thông báo
        $title = "Yêu cầu trả hàng tự động từ {$store->name}";
        $message = "Cửa hàng {$store->name} yêu cầu trả {$totalItems} sản phẩm về kho. " .
                  "Đây là yêu cầu được tạo tự động dựa trên điều kiện kinh doanh của cửa hàng.";

        // Tạo thông báo
        $notification = Notification::create([
            'store_id' => $store->id,
            'type' => 'return_request',
            'title' => $title,
            'message' => $message,
            'data' => [
                'products' => $products,
                'requested_at' => now(),
                'priority' => $this->getRandomPriority(),
                'auto_generated' => true,
                'generation_time' => now()->toISOString(),
                'total_items' => $totalItems,
                'product_count' => count($products)
            ],
            'status' => 'pending'
        ]);

        // Ghi log việc tạo
        Log::info('Auto-generated return request', [
            'notification_id' => $notification->id,
            'store_id' => $store->id,
            'store_name' => $store->name,
            'product_count' => count($products),
            'total_items' => $totalItems
        ]);

        return $notification;
    }

    /**
     * Get random priority for the request
     */
    private function getRandomPriority()
    {
        $priorities = ['normal' => 70, 'high' => 25, 'urgent' => 5];
        $rand = rand(1, 100);
        
        if ($rand <= 5) {
            return 'urgent';
        } elseif ($rand <= 30) {
            return 'high';
        } else {
            return 'normal';
        }
    }
}
