<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Store;
use App\Models\Product;
use App\Models\Notification;
use App\Models\Warehouse;

class AutoGenerationController extends Controller
{    /**
     * Hiển thị bảng điều khiển tự động tạo
     */
    public function index()
    {
        return view('admin.auto-generation.index');
    }    /**
     * Tạo đơn trả hàng thử nghiệm
     */
    public function createTestReturnOrders(Request $request)
    {
        $request->validate([
            'count' => 'required|integer|min:1|max:10'
        ]);

        try {            $count = $request->count;
            $createdOrders = 0;
            
            // Lấy các cửa hàng ngẫu nhiên
            $stores = Store::where('status', true)->inRandomOrder()->limit($count)->get();
            
            if ($stores->isEmpty()) {
                return redirect()->back()->with('error', 'Không có cửa hàng nào để tạo đơn thử nghiệm.');
            }

            // Lấy các sản phẩm có sẵn
            $products = Product::all();
            
            if ($products->count() < 3) {
                return redirect()->back()->with('error', 'Cần ít nhất 3 sản phẩm để tạo đơn thử nghiệm.');
            }

            DB::beginTransaction();            foreach ($stores as $store) {
                // Thêm các sản phẩm ngẫu nhiên (1-3 sản phẩm mỗi đơn)
                $numProducts = rand(1, 3);
                $selectedProducts = $products->random($numProducts);

                $productsData = [];
                foreach ($selectedProducts as $product) {
                    $productsData[] = [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'product_sku' => $product->sku,
                        'quantity' => rand(1, 10),
                        'reason' => 'Thử nghiệm',
                        'notes' => 'Sản phẩm thử nghiệm'
                    ];                }                // Tạo thông báo yêu cầu trả hàng
                Notification::create([
                    'store_id' => $store->id,
                    'type' => 'return_request',
                    'title' => 'Đơn trả hàng thử nghiệm từ ' . $store->name,
                    'message' => 'Đơn trả hàng thử nghiệm được tạo tự động',
                    'status' => 'pending',
                    'data' => [
                        'products' => $productsData,
                        'auto_generated' => true,
                        'generation_type' => 'test_return',
                        'notes' => 'Đơn trả hàng thử nghiệm được tạo tự động',
                        'requested_date' => now()->toDateString(),
                        'expected_date' => now()->addDays(3)->toDateString()
                    ]
                ]);

                $createdOrders++;
            }

            DB::commit();

            return redirect()->back()->with('success', "Đã tạo thành công {$createdOrders} đơn trả hàng thử nghiệm!");

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Test return order creation failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }    /**
     * Tạo đơn gửi hàng thử nghiệm
     */
    public function createTestShipmentOrders(Request $request)
    {
        $request->validate([
            'count' => 'required|integer|min:1|max:10'
        ]);

        try {
            $count = $request->count;
            $createdOrders = 0;
              // Lấy cửa hàng ngẫu nhiên
            $stores = Store::where('status', true)->inRandomOrder()->limit($count)->get();
            
            if ($stores->isEmpty()) {
                return redirect()->back()->with('error', 'Không có cửa hàng nào để tạo đơn thử nghiệm.');
            }

            // Lấy sản phẩm có sẵn
            $products = Product::all();
            
            if ($products->count() < 5) {
                return redirect()->back()->with('error', 'Cần ít nhất 5 sản phẩm để tạo đơn thử nghiệm.');
            }

            DB::beginTransaction();            foreach ($stores as $store) {
                // Thêm các sản phẩm ngẫu nhiên (1-5 sản phẩm mỗi đơn)
                $numProducts = rand(1, 5);
                $selectedProducts = $products->random($numProducts);

                $productsData = [];
                $totalValue = 0;
                foreach ($selectedProducts as $product) {
                    $quantity = rand(5, 50);                    $unitPrice = $product->price ?? rand(50000, 2000000); // Sử dụng giá sản phẩm hoặc ngẫu nhiên nếu không được đặt
                    $totalPrice = $unitPrice * $quantity;
                    
                    $productsData[] = [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'product_sku' => $product->sku,
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'total_price' => $totalPrice,
                        'notes' => 'Sản phẩm thử nghiệm'
                    ];
                    
                    $totalValue += $totalPrice;
                }                // Lấy kho để chỉ định
                $warehouse = Warehouse::first();                // Tạo thông báo yêu cầu gửi hàng
                Notification::create([
                    'store_id' => $store->id,
                    'warehouse_id' => $warehouse ? $warehouse->id : null,
                    'type' => 'receive_request',
                    'title' => 'Đơn gửi hàng thử nghiệm từ ' . $store->name,
                    'message' => 'Đơn gửi hàng thử nghiệm được tạo tự động',
                    'status' => 'pending',
                    'data' => [
                        'products' => $productsData,
                        'total_value' => $totalValue,
                        'product_count' => count($productsData),
                        'auto_generated' => true,
                        'generation_type' => 'test_shipment',
                        'notes' => 'Đơn gửi hàng thử nghiệm được tạo tự động',
                        'requested_date' => now()->toDateString(),
                        'expected_date' => now()->addDays(5)->toDateString()
                    ]
                ]);

                $createdOrders++;
            }

            DB::commit();

            return redirect()->back()->with('success', "Đã tạo thành công {$createdOrders} đơn gửi hàng thử nghiệm!");

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Test shipment order creation failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}
