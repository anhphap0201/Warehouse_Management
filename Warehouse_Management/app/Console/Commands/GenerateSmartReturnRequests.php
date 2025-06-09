<?php

namespace App\Console\Commands;

use App\Models\Store;
use App\Models\Notification;
use App\Models\StoreInventory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GenerateSmartReturnRequests extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'stores:smart-return-requests 
                           {--dry-run : Show what would be generated without creating requests}
                           {--min-overstock-days=30 : Minimum days a product should be overstocked}
                           {--low-turnover-threshold=0.1 : Products with turnover below this will be returned}';

    /**
     * The console command description.
     */
    protected $description = 'Generate intelligent return requests based on store inventory conditions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        
        if ($isDryRun) {
            $this->info('🧪 DRY RUN MODE - Không có yêu cầu nào được tạo thực sự');
        }
        
        $this->info('🧠 Bắt đầu phân tích thông minh để tạo yêu cầu trả hàng...');
        
        $stores = Store::where('status', true)
            ->whereHas('inventory', function ($q) {
                $q->where('quantity', '>', 0);
            })
            ->with(['inventory' => function ($q) {
                $q->where('quantity', '>', 0)->with('product');
            }])
            ->get();

        if ($stores->isEmpty()) {
            $this->warn('❌ Không tìm thấy cửa hàng nào có tồn kho.');
            return Command::SUCCESS;
        }

        $this->info("📋 Phân tích {$stores->count()} cửa hàng...");

        $totalRequests = 0;
        $totalStoresAffected = 0;

        foreach ($stores as $store) {
            $analysis = $this->analyzeStoreInventory($store);
            
            if (!empty($analysis['issues'])) {
                $totalStoresAffected++;
                
                $this->info("🏪 Cửa hàng: {$store->name}");
                foreach ($analysis['issues'] as $issue) {
                    $this->line("   - {$issue}");
                }
                
                if (!$isDryRun) {
                    $request = $this->generateIntelligentReturnRequest($store, $analysis);
                    if ($request) {
                        $totalRequests++;
                        $this->info("✅ Tạo yêu cầu trả hàng (ID: {$request->id})");
                    }
                } else {
                    $this->info("   📝 Sẽ tạo yêu cầu trả hàng cho các sản phẩm có vấn đề");
                    $totalRequests++;
                }
            }
        }

        if ($isDryRun) {
            $this->info("🧪 DRY RUN KẾT QUẢ:");
            $this->info("   - Cửa hàng cần can thiệp: {$totalStoresAffected}");
            $this->info("   - Yêu cầu sẽ được tạo: {$totalRequests}");
        } else {
            $this->info("🎉 Hoàn thành! Đã tạo {$totalRequests} yêu cầu trả hàng thông minh cho {$totalStoresAffected} cửa hàng");
        }
        
        return Command::SUCCESS;
    }

    /**
     * Analyze store inventory for potential issues
     */
    private function analyzeStoreInventory(Store $store)
    {
        $issues = [];
        $problematicProducts = [];
        $minOverstockDays = (int) $this->option('min-overstock-days');
        
        foreach ($store->inventory as $inventory) {
            $product = $inventory->product;
            $issues_for_product = [];

            // Kiểm tra xem có tồn kho quá mức không
            if ($inventory->isOverstocked()) {
                $overstockAmount = $inventory->quantity - $inventory->max_stock;
                $issues_for_product[] = "Tồn kho vượt mức cho phép (+{$overstockAmount})";
                
                $problematicProducts[] = [
                    'inventory' => $inventory,
                    'issue_type' => 'overstock',
                    'severity' => 'high',
                    'return_quantity' => min($overstockAmount, $inventory->quantity - $inventory->min_stock),
                    'reason' => "Tồn kho vượt mức: {$inventory->quantity}/{$inventory->max_stock}"
                ];
            }

            // Kiểm tra xem có sắp hết hạn không (mô phỏng - bạn có thể có ngày hết hạn thực tế)
            if ($this->isNearExpiry($inventory)) {
                $issues_for_product[] = "Sản phẩm sắp hết hạn";
                
                $problematicProducts[] = [
                    'inventory' => $inventory,
                    'issue_type' => 'near_expiry',
                    'severity' => 'urgent',
                    'return_quantity' => min(5, $inventory->quantity),
                    'reason' => "Sản phẩm sắp hết hạn sử dụng"
                ];
            }

            // Kiểm tra xem có bán chậm không (mô phỏng dựa trên tỷ lệ số lượng với tồn kho tối đa)
            if ($this->isSlowMoving($inventory)) {
                $issues_for_product[] = "Sản phẩm bán chậm";
                
                $problematicProducts[] = [
                    'inventory' => $inventory,
                    'issue_type' => 'slow_moving',
                    'severity' => 'medium',
                    'return_quantity' => min(3, $inventory->quantity - $inventory->min_stock),
                    'reason' => "Sản phẩm bán chậm, cần giảm tồn kho"
                ];
            }

            if (!empty($issues_for_product)) {
                $issues[] = "{$product->name} ({$product->sku}): " . implode(', ', $issues_for_product);
            }
        }

        return [
            'issues' => $issues,
            'problematic_products' => $problematicProducts
        ];
    }

    /**
     * Check if product is near expiry (simulated logic)
     */
    private function isNearExpiry($inventory)
    {
        // Mô phỏng: giả sử các sản phẩm đã tồn kho hơn 90 ngày là sắp hết hạn
        // Trong triển khai thực tế, bạn sẽ kiểm tra ngày hết hạn thực tế
        return $inventory->updated_at < now()->subDays(90) && $inventory->quantity > $inventory->min_stock;
    }

    /**
     * Check if product is slow moving
     */
    private function isSlowMoving($inventory)
    {
        // Mô phỏng: nếu số lượng gần với max_stock thì có thể bán chậm
        $threshold = $this->option('low-turnover-threshold');
        $stockRatio = $inventory->quantity / max($inventory->max_stock, 1);
        
        return $stockRatio > (1 - $threshold) && $inventory->quantity > $inventory->min_stock;
    }

    /**
     * Generate intelligent return request based on analysis
     */
    private function generateIntelligentReturnRequest(Store $store, array $analysis)
    {
        $problematicProducts = $analysis['problematic_products'];
        
        if (empty($problematicProducts)) {
            return null;
        }

        // Sắp xếp theo mức độ nghiêm trọng
        usort($problematicProducts, function ($a, $b) {
            $severityOrder = ['urgent' => 3, 'high' => 2, 'medium' => 1, 'low' => 0];
            return ($severityOrder[$b['severity']] ?? 0) - ($severityOrder[$a['severity']] ?? 0);
        });

        $products = [];
        $totalItems = 0;
        $highestSeverity = 'normal';

        foreach ($problematicProducts as $item) {
            if ($item['return_quantity'] <= 0) continue;

            $inventory = $item['inventory'];
            $product = $inventory->product;

            $products[] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_sku' => $product->sku,
                'quantity' => $item['return_quantity'],
                'reason' => $item['reason'],
                'issue_type' => $item['issue_type'],
                'severity' => $item['severity'],
                'available_quantity' => $inventory->quantity,
                'current_stock_level' => [
                    'quantity' => $inventory->quantity,
                    'min_stock' => $inventory->min_stock,
                    'max_stock' => $inventory->max_stock
                ]
            ];

            $totalItems += $item['return_quantity'];

            // Xác định mức độ ưu tiên tổng thể
            if ($item['severity'] === 'urgent') {
                $highestSeverity = 'urgent';
            } elseif ($item['severity'] === 'high' && $highestSeverity !== 'urgent') {
                $highestSeverity = 'high';
            }
        }

        if (empty($products)) {
            return null;
        }

        // Tạo tiêu đề và thông báo
        $urgentCount = count(array_filter($products, fn($p) => $p['severity'] === 'urgent'));
        $titlePrefix = $urgentCount > 0 ? "🚨 KHẨN CẤP" : "📋 Tối ưu hóa";
        
        $title = "{$titlePrefix}: Yêu cầu trả hàng thông minh từ {$store->name}";
        
        $message = "Hệ thống đã phát hiện {$store->name} cần trả {$totalItems} sản phẩm về kho dựa trên phân tích tình hình kinh doanh. ";
        
        if ($urgentCount > 0) {
            $message .= "Có {$urgentCount} sản phẩm cần xử lý khẩn cấp. ";
        }
        
        $message .= "Việc thực hiện yêu cầu này sẽ giúp tối ưu hóa tồn kho và giảm rủi ro kinh doanh.";

        // Tạo thông báo
        $notification = Notification::create([
            'store_id' => $store->id,
            'type' => 'return_request',
            'title' => $title,
            'message' => $message,
            'data' => [
                'products' => $products,
                'requested_at' => now(),
                'priority' => $highestSeverity,
                'auto_generated' => true,
                'generation_type' => 'smart_analysis',
                'generation_time' => now()->toISOString(),
                'total_items' => $totalItems,
                'product_count' => count($products),
                'analysis_summary' => [
                    'urgent_items' => $urgentCount,
                    'total_issues' => count($analysis['issues']),
                    'issue_types' => array_unique(array_column($products, 'issue_type'))
                ]
            ],
            'status' => 'pending'
        ]);

        // Ghi log việc tạo
        Log::info('Smart return request generated', [
            'notification_id' => $notification->id,
            'store_id' => $store->id,
            'store_name' => $store->name,
            'severity' => $highestSeverity,
            'product_count' => count($products),
            'total_items' => $totalItems,
            'urgent_items' => $urgentCount
        ]);

        return $notification;
    }
}
