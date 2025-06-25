<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\Store;
use App\Models\Warehouse;
use App\Models\Product;
use Faker\Factory as Faker;

class NotificationDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('vi_VN');
        
        // Lấy dữ liệu có sẵn
        $stores = Store::all();
        $warehouses = Warehouse::all();
        $products = Product::all();
        
        if ($stores->isEmpty() || $warehouses->isEmpty() || $products->isEmpty()) {
            $this->command->warn('Cần có ít nhất 1 cửa hàng, 1 kho hàng và 1 sản phẩm để tạo notifications demo');
            return;
        }
        
        // Tạo 15-20 notifications demo
        for ($i = 0; $i < rand(15, 20); $i++) {
            $store = $stores->random();
            $warehouse = $warehouses->random();
            $notificationType = $faker->randomElement(['return', 'shipment']);
            
            if ($notificationType === 'return') {
                // Thông báo trả hàng từ cửa hàng về kho
                $product = $products->random();
                $quantity = rand(5, 50);
                $reason = $faker->randomElement([
                    'Hàng hóa bị lỗi kỹ thuật',
                    'Sản phẩm không đúng quy cách',
                    'Hàng tồn kho quá lâu',
                    'Khách hàng trả lại nhiều',
                    'Sản phẩm bị hư hỏng trong vận chuyển',
                    'Không bán được, cần trả về kho',
                    'Hết hạn sử dụng gần',
                    'Có sản phẩm mới thay thế'
                ]);
                
                Notification::create([
                    'title' => "Yêu cầu trả hàng từ cửa hàng {$store->name}",
                    'message' => "Cửa hàng {$store->name} yêu cầu trả lại {$quantity} {$product->unit} sản phẩm '{$product->name}' về kho {$warehouse->name}. Lý do: {$reason}",
                    'type' => 'return_request',
                    'status' => $faker->randomElement(['pending', 'pending', 'pending', 'approved', 'rejected']),
                    'store_id' => $store->id,
                    'warehouse_id' => $warehouse->id,
                    'data' => json_encode([
                        'store_id' => $store->id,
                        'store_name' => $store->name,
                        'warehouse_id' => $warehouse->id,
                        'warehouse_name' => $warehouse->name,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'quantity' => $quantity,
                        'unit' => $product->unit,
                        'reason' => $reason,
                        'request_type' => 'return',
                        'priority' => $faker->randomElement(['low', 'normal', 'normal', 'high'])
                    ]),
                    'created_at' => $faker->dateTimeBetween('-30 days', 'now'),
                    'read_at' => $faker->optional(0.3)->dateTimeBetween('-25 days', 'now'),
                ]);
            } else {
                // Thông báo yêu cầu gửi hàng từ kho tới cửa hàng
                $product = $products->random();
                $quantity = rand(10, 100);
                $reason = $faker->randomElement([
                    'Cửa hàng sắp hết hàng',
                    'Đặt hàng bổ sung cho mùa cao điểm',
                    'Yêu cầu nhập hàng định kỳ',
                    'Sản phẩm bán chạy cần bổ sung',
                    'Chuẩn bị cho chương trình khuyến mãi',
                    'Khai trương chi nhánh mới',
                    'Khách hàng đặt hàng số lượng lớn',
                    'Bổ sung hàng sau khi kiểm kê'
                ]);
                
                Notification::create([
                    'title' => "Yêu cầu gửi hàng tới cửa hàng {$store->name}",
                    'message' => "Cửa hàng {$store->name} yêu cầu gửi {$quantity} {$product->unit} sản phẩm '{$product->name}' từ kho {$warehouse->name}. Lý do: {$reason}",
                    'type' => 'receive_request',
                    'status' => $faker->randomElement(['pending', 'pending', 'pending', 'approved', 'rejected']),
                    'store_id' => $store->id,
                    'warehouse_id' => $warehouse->id,
                    'data' => json_encode([
                        'store_id' => $store->id,
                        'store_name' => $store->name,
                        'warehouse_id' => $warehouse->id,
                        'warehouse_name' => $warehouse->name,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'quantity' => $quantity,
                        'unit' => $product->unit,
                        'reason' => $reason,
                        'request_type' => 'shipment',
                        'priority' => $faker->randomElement(['low', 'normal', 'normal', 'high', 'urgent'])
                    ]),
                    'created_at' => $faker->dateTimeBetween('-30 days', 'now'),
                    'read_at' => $faker->optional(0.3)->dateTimeBetween('-25 days', 'now'),
                ]);
            }
        }
        
        $this->command->info('Đã tạo thành công ' . rand(15, 20) . ' notifications demo cho trả hàng và gửi hàng!');
    }
}
