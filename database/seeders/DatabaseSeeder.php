<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Warehouse;
use App\Models\Store;
use App\Models\Supplier;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a default admin user
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);

        // Create sample categories
        $categories = [
            'Điện tử',
            'Thực phẩm',
            'Quần áo',
            'Gia dụng',
            'Sách',
            'Đồ chơi'
        ];

        foreach ($categories as $categoryName) {
            Category::create(['name' => $categoryName]);
        }

        // Create sample warehouses
        $warehouses = [
            ['name' => 'Kho Hà Nội', 'location' => 'Hà Nội'],
            ['name' => 'Kho TP.HCM', 'location' => 'TP. Hồ Chí Minh'],
            ['name' => 'Kho Đà Nẵng', 'location' => 'Đà Nẵng'],
        ];

        foreach ($warehouses as $warehouse) {
            Warehouse::create($warehouse);
        }

        // Create sample stores
        $stores = [
            [
                'name' => 'Cửa hàng Trung tâm',
                'address' => 'Số 123, Đường ABC, Quận 1, TP.HCM',
                'phone' => '0901234567',
                'email' => 'store1@example.com'
            ],
            [
                'name' => 'Cửa hàng Bắc',
                'address' => 'Số 456, Đường XYZ, Ba Đình, Hà Nội',
                'phone' => '0901234568',
                'email' => 'store2@example.com'
            ]
        ];

        foreach ($stores as $store) {
            Store::create($store);
        }

        // Create sample suppliers
        $suppliers = [
            [
                'name' => 'Nhà cung cấp ABC',
                'email' => 'abc@supplier.com',
                'phone' => '0901111111',
                'address' => 'Số 789, Đường ABC, Quận Thủ Đức, TP.HCM'
            ],
            [
                'name' => 'Nhà cung cấp XYZ',
                'email' => 'xyz@supplier.com',
                'phone' => '0902222222',
                'address' => 'Số 101, Đường XYZ, Cầu Giấy, Hà Nội'
            ]
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}
