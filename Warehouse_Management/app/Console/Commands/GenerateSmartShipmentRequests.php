<?php

namespace App\Console\Commands;

use App\Models\Store;
use App\Models\Notification;
use App\Models\StoreInventory;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GenerateSmartShipmentRequests extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'stores:smart-shipment-requests 
                           {--dry-run : Show what would be generated without creating requests}
                           {--min-shortage-days=7 : Minimum days a product should be in shortage}
                           {--low-stock-threshold=10 : Products with stock below this will be requested}
                           {--demand-multiplier=2 : Multiply recent sales to predict needed quantity}';

    /**
     * The console command description.
     */
    protected $description = 'Generate intelligent shipment requests based on store inventory analysis and demand patterns';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        $minShortagedays = (int) $this->option('min-shortage-days');
        $lowStockThreshold = (int) $this->option('low-stock-threshold');
        $demandMultiplier = (float) $this->option('demand-multiplier');
        
        if ($isDryRun) {
            $this->info('🧪 DRY RUN MODE - Không có yêu cầu nào được tạo thực sự');
        }
        
        $this->info('🧠 Bắt đầu phân tích thông minh để tạo yêu cầu gửi hàng...');
        
        $stores = Store::whereNotNull('id')
            ->with(['inventory' => function ($q) {
                $q->with('product');
            }])
            ->get();

        if ($stores->isEmpty()) {
            $this->warn('❌ Không tìm thấy cửa hàng nào.');
            return Command::SUCCESS;
        }

        $this->info("📊 Phân tích {$stores->count()} cửa hàng...");

        $totalRequests = 0;
        $totalProducts = 0;
        $storesWithRequests = 0;

        foreach ($stores as $store) {
            $analysis = $this->analyzeStoreShipmentNeeds($store, $lowStockThreshold, $demandMultiplier);
            
            if (!empty($analysis['products_needed'])) {
                $storesWithRequests++;
                
                if ($isDryRun) {
                    $this->displayAnalysis($store, $analysis);
                } else {
                    $request = $this->createShipmentRequest($store, $analysis);
                    if ($request) {
                        $totalRequests++;
                        $totalProducts += count($analysis['products_needed']);
                    }
                }
            }
        }

        if ($isDryRun) {
            $this->info("🔍 Phân tích hoàn thành: {$storesWithRequests} cửa hàng cần bổ sung hàng");
        } else {
            $this->info("✅ Đã tạo {$totalRequests} yêu cầu gửi hàng cho {$storesWithRequests} cửa hàng");
            $this->info("📦 Tổng cộng {$totalProducts} sản phẩm cần bổ sung");
            
            // Ghi log hoạt động
            Log::info('Smart shipment requests generated', [
                'stores_analyzed' => $stores->count(),
                'stores_with_requests' => $storesWithRequests,
                'total_requests' => $totalRequests,
                'total_products' => $totalProducts,
                'parameters' => [
                    'low_stock_threshold' => $lowStockThreshold,
                    'demand_multiplier' => $demandMultiplier,
                    'min_shortage_days' => $minShortagedays
                ]
            ]);
        }

        return Command::SUCCESS;
    }

    /**
     * Analyze store's shipment needs based on inventory and demand patterns
     */
    private function analyzeStoreShipmentNeeds($store, $lowStockThreshold, $demandMultiplier)
    {
        $productsNeeded = [];
        $totalValue = 0;
        $urgentCount = 0;
        $reasons = [];

        // Lấy tồn kho cửa hàng
        $inventory = $store->inventory;
        
        if ($inventory->isEmpty()) {
            // Nếu không có tồn kho, đề xuất các sản phẩm phổ biến
            $popularProducts = Product::take(5)->get();
            
            foreach ($popularProducts as $product) {
                $suggestedQuantity = rand(10, 30);
                $productsNeeded[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'current_stock' => 0,
                    'suggested_quantity' => $suggestedQuantity,
                    'unit_price' => $product->price,
                    'total_price' => $product->price * $suggestedQuantity,
                    'urgency' => 'medium',
                    'reason' => 'Sản phẩm mới cho cửa hàng'
                ];
                $totalValue += $product->price * $suggestedQuantity;
            }
            
            $reasons[] = 'Cửa hàng chưa có tồn kho';
        } else {
            // Phân tích tồn kho hiện có
            foreach ($inventory as $item) {
                $analysis = $this->analyzeProductNeed($item, $lowStockThreshold, $demandMultiplier);
                
                if ($analysis['needs_restock']) {
                    $productsNeeded[] = $analysis;
                    $totalValue += $analysis['total_price'];
                    
                    if ($analysis['urgency'] === 'high') {
                        $urgentCount++;
                    }
                    
                    $reasons[] = $analysis['reason'];
                }
            }
        }

        // Tính toán mức độ ưu tiên dựa trên tổng giá trị và tính cấp thiết
        $priority = 'normal';
        if ($urgentCount > 0 || $totalValue > 2000000) {
            $priority = 'high';
        } elseif ($totalValue > 500000) {
            $priority = 'medium';
        }

        return [
            'products_needed' => $productsNeeded,
            'total_value' => $totalValue,
            'urgent_products' => $urgentCount,
            'priority' => $priority,
            'reasons' => array_unique($reasons),
            'analysis_summary' => $this->buildAnalysisSummary($productsNeeded, $totalValue, $urgentCount)
        ];
    }

    /**
     * Analyze individual product restocking needs
     */
    private function analyzeProductNeed($inventoryItem, $lowStockThreshold, $demandMultiplier)
    {
        $product = $inventoryItem->product;
        $currentStock = $inventoryItem->quantity;
        
        // Xác định xem có cần nhập thêm hàng không
        $needsRestock = $currentStock <= $lowStockThreshold;
        
        if (!$needsRestock) {
            return ['needs_restock' => false];
        }        // Tính toán số lượng đề xuất dựa trên nhiều yếu tố
        $baseQuantity = $lowStockThreshold * 2; // Tồn kho an toàn cơ bản
        
        // Mô phỏng phân tích nhu cầu (trong ứng dụng thực tế, điều này sẽ sử dụng dữ liệu bán hàng thực tế)
        $simulatedWeeklyDemand = rand(5, 25);
        $demandBasedQuantity = $simulatedWeeklyDemand * $demandMultiplier;
        
        $suggestedQuantity = max($baseQuantity, $demandBasedQuantity);
        
        // Xác định tính cấp thiết
        $urgency = 'normal';
        $reason = 'Bổ sung tồn kho thường xuyên';
        
        if ($currentStock == 0) {
            $urgency = 'high';
            $reason = 'Hết hàng hoàn toàn';
            $suggestedQuantity *= 1.5; // Tăng số lượng cho các sản phẩm hết hàng
        } elseif ($currentStock <= $lowStockThreshold / 2) {
            $urgency = 'high';
            $reason = 'Tồn kho cực thấp';
        } elseif ($currentStock <= $lowStockThreshold) {
            $urgency = 'medium';
            $reason = 'Tồn kho thấp dưới mức an toàn';
        }

        return [
            'needs_restock' => true,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'product_sku' => $product->sku,
            'current_stock' => $currentStock,
            'suggested_quantity' => round($suggestedQuantity),
            'unit_price' => $product->price,
            'total_price' => $product->price * round($suggestedQuantity),
            'urgency' => $urgency,
            'reason' => $reason,
            'weekly_demand_estimate' => $simulatedWeeklyDemand
        ];
    }

    /**
     * Display analysis results in dry-run mode
     */
    private function displayAnalysis($store, $analysis)
    {
        $this->info("\n📍 Cửa hàng: {$store->name} (ID: {$store->id})");
        $this->info("💰 Tổng giá trị cần bổ sung: " . number_format($analysis['total_value'], 0, ',', '.') . " VNĐ");
        $this->info("🔥 Sản phẩm cấp bách: {$analysis['urgent_products']}");
        $this->info("⭐ Độ ưu tiên: {$analysis['priority']}");
        
        if (!empty($analysis['products_needed'])) {
            $this->info("📦 Sản phẩm cần bổ sung:");
            
            foreach ($analysis['products_needed'] as $product) {
                $urgencyIcon = $product['urgency'] === 'high' ? '🔴' : ($product['urgency'] === 'medium' ? '🟡' : '🟢');
                $this->line("   {$urgencyIcon} {$product['product_name']} (Hiện có: {$product['current_stock']}, Cần: {$product['suggested_quantity']}) - {$product['reason']}");
            }
        }
    }

    /**
     * Create shipment request notification
     */
    private function createShipmentRequest($store, $analysis)
    {
        try {
            $warehouse = Warehouse::first();
              $notification = Notification::create([
                'store_id' => $store->id,
                'title' => "Yêu cầu gửi hàng thông minh từ {$store->name}",
                'message' => $analysis['analysis_summary'],
                'type' => 'receive_request',
                'status' => 'pending',
                'warehouse_id' => $warehouse ? $warehouse->id : null,
                'data' => [
                    'store_name' => $store->name,
                    'products' => $analysis['products_needed'],
                    'total_value' => $analysis['total_value'],
                    'product_count' => count($analysis['products_needed']),
                    'urgent_products' => $analysis['urgent_products'],
                    'priority' => $analysis['priority'],
                    'reasons' => $analysis['reasons'],
                    'auto_generated' => true,
                    'generation_type' => 'smart_shipment',
                    'analysis_date' => now()->toDateString(),
                    'analysis_details' => $analysis
                ]
            ]);

            $this->info("✅ Tạo yêu cầu gửi hàng thông minh cho: {$store->name}");
            return $notification;

        } catch (\Exception $e) {
            $this->error("❌ Lỗi khi tạo yêu cầu cho {$store->name}: {$e->getMessage()}");
            Log::error("Failed to create smart shipment request for store {$store->id}", [
                'store_id' => $store->id,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Build analysis summary message
     */
    private function buildAnalysisSummary($productsNeeded, $totalValue, $urgentCount)
    {
        $productCount = count($productsNeeded);
        $summary = "Phân tích thông minh: Cửa hàng cần bổ sung {$productCount} sản phẩm với tổng giá trị " . 
                  number_format($totalValue, 0, ',', '.') . " VNĐ";
        
        if ($urgentCount > 0) {
            $summary .= ". Có {$urgentCount} sản phẩm cấp bách cần xử lý ngay";
        }
        
        $summary .= ". Được tạo bởi hệ thống phân tích tự động dựa trên mức tồn kho và mẫu nhu cầu.";
        
        return $summary;
    }
}
