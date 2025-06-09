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

class GenerateRandomShipmentRequests extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'stores:generate-shipment-requests 
                           {--stores=* : Specific store IDs to generate requests for}
                           {--percentage=30 : Percentage of active stores that should generate requests}
                           {--min-products=1 : Minimum number of products to request}
                           {--max-products=5 : Maximum number of products to request}
                           {--min-quantity=5 : Minimum quantity per product}
                           {--max-quantity=50 : Maximum quantity per product}';

    /**
     * The console command description.
     */
    protected $description = 'Generate random shipment requests from stores to warehouse';

    /**
     * List of possible shipment reasons
     */
    private $shipmentReasons = [
        'Hàng sắp hết trong kho',
        'Nhu cầu khách hàng tăng cao',
        'Sản phẩm bán chạy cần bổ sung',
        'Chuẩn bị cho đợt khuyến mãi',
        'Bổ sung hàng theo kế hoạch',
        'Khách hàng đặt hàng số lượng lớn',
        'Hàng tồn kho thấp dưới mức an toàn',
        'Chuẩn bị cho mùa kinh doanh cao điểm',
        'Yêu cầu bổ sung từ bộ phận bán hàng',
        'Dự trữ hàng cho cuối tuần',
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚚 Bắt đầu tạo yêu cầu gửi hàng ngẫu nhiên...');

        try {
            $storeIds = $this->option('stores');
            $percentage = max(1, min(100, (int) $this->option('percentage')));
            $minProducts = max(1, (int) $this->option('min-products'));
            $maxProducts = max($minProducts, (int) $this->option('max-products'));
            $minQuantity = max(1, (int) $this->option('min-quantity'));
            $maxQuantity = max($minQuantity, (int) $this->option('max-quantity'));

            // Lấy các cửa hàng mục tiêu
            $stores = $this->getTargetStores($storeIds, $percentage);
            
            if ($stores->isEmpty()) {
                $this->warn('⚠️  Không tìm thấy cửa hàng nào để tạo yêu cầu gửi hàng');
                return 0;
            }

            $this->info("📋 Tìm thấy {$stores->count()} cửa hàng có thể tạo yêu cầu gửi hàng");

            $targetCount = max(1, ceil($stores->count() * $percentage / 100));
            $selectedStores = $stores->random($targetCount);

            $this->info("🎯 Sẽ tạo yêu cầu cho {$selectedStores->count()} cửa hàng ({$percentage}%)");

            $successCount = 0;
            $totalRequests = 0;

            foreach ($selectedStores as $store) {
                $request = $this->generateShipmentRequestForStore(
                    $store, 
                    $minProducts, 
                    $maxProducts, 
                    $minQuantity, 
                    $maxQuantity
                );

                if ($request) {
                    $successCount++;
                    $totalRequests++;
                    $this->info("✅ Tạo yêu cầu gửi hàng cho cửa hàng: {$store->name} (ID: {$store->id})");
                } else {
                    $this->warn("⚠️  Không thể tạo yêu cầu cho cửa hàng: {$store->name}");
                }
            }

            $this->info("🎉 Hoàn thành! Đã tạo {$totalRequests} yêu cầu gửi hàng từ {$successCount} cửa hàng");

            // Ghi log hoạt động
            Log::info('Random shipment requests generated', [
                'stores_processed' => $selectedStores->count(),
                'requests_created' => $totalRequests,
                'success_rate' => $successCount / $selectedStores->count() * 100,
                'parameters' => [
                    'percentage' => $percentage,
                    'min_products' => $minProducts,
                    'max_products' => $maxProducts,
                    'min_quantity' => $minQuantity,
                    'max_quantity' => $maxQuantity,
                ]
            ]);

            return 0;

        } catch (\Exception $e) {
            $this->error("❌ Lỗi trong quá trình tạo yêu cầu: " . $e->getMessage());
            Log::error('Random shipment request generation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }
    }

    /**
     * Get target stores for generating requests
     */
    private function getTargetStores($storeIds, $percentage)
    {
        $query = Store::whereNotNull('id');

        if (!empty($storeIds)) {
            $query->whereIn('id', $storeIds);
        }

        return $query->get();
    }

    /**
     * Generate a shipment request for a specific store
     */
    private function generateShipmentRequestForStore($store, $minProducts, $maxProducts, $minQuantity, $maxQuantity)
    {
        try {
            // Lấy các sản phẩm có sẵn (những sản phẩm tồn tại trong hệ thống)
            $availableProducts = Product::whereHas('storeInventories', function($query) use ($store) {
                $query->where('store_id', $store->id);
            })->get();

            if ($availableProducts->isEmpty()) {
                // Nếu không có tồn kho cửa hàng, lấy bất kỳ sản phẩm nào
                $availableProducts = Product::take(10)->get();
            }

            if ($availableProducts->isEmpty()) {
                return null;
            }

            // Xác định số lượng sản phẩm cần yêu cầu
            $productCount = rand($minProducts, min($maxProducts, $availableProducts->count()));
            $selectedProducts = $availableProducts->random($productCount);

            // Xây dựng dữ liệu yêu cầu
            $products = [];
            $totalValue = 0;            foreach ($selectedProducts as $product) {
                $quantity = rand($minQuantity, $maxQuantity);
                // Sử dụng giá ngẫu nhiên vì sản phẩm không có giá cố định
                $unitPrice = rand(50000, 2000000); // Giá ngẫu nhiên từ 50k đến 2M VND
                $products[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $unitPrice * $quantity,
                ];
                $totalValue += $unitPrice * $quantity;
            }

            // Chọn lý do ngẫu nhiên
            $reason = $this->shipmentReasons[array_rand($this->shipmentReasons)];

            // Lấy kho chính (hoặc kho đầu tiên có sẵn)
            $warehouse = Warehouse::first();            // Tạo thông báo
            $notification = Notification::create([
                'store_id' => $store->id,
                'title' => "Yêu cầu gửi hàng tự động từ {$store->name}",
                'message' => "Cửa hàng {$store->name} yêu cầu gửi {$productCount} sản phẩm với tổng giá trị " . number_format($totalValue, 0, ',', '.') . " VNĐ. Lý do: {$reason}",
                'type' => 'receive_request',
                'status' => 'pending',
                'warehouse_id' => $warehouse ? $warehouse->id : null,
                'data' => [
                    'store_id' => $store->id,
                    'store_name' => $store->name,
                    'products' => $products,
                    'total_value' => $totalValue,
                    'product_count' => $productCount,
                    'reason' => $reason,
                    'auto_generated' => true,
                    'generation_type' => 'random_shipment',
                    'priority' => $totalValue > 1000000 ? 'high' : 'normal',
                    'request_date' => now()->toDateString(),
                ]
            ]);

            return $notification;

        } catch (\Exception $e) {
            Log::error("Failed to generate shipment request for store {$store->id}", [
                'store_id' => $store->id,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }
}
