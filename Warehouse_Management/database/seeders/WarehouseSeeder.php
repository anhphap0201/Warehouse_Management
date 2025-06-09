<?php

namespace Database\Seeders;

use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $warehouses = [
            [
                'name' => 'Kho Trung Tâm TP.HCM',
                'code' => 'KH-HCM-001',
                'location' => 'Quận 1, TP. Hồ Chí Minh',
                'address' => '123 Đường Nguyễn Huệ, Phường Bến Nghé, Quận 1, TP. Hồ Chí Minh',
                'manager' => 'Nguyễn Văn A',
                'phone' => '0901234567',
                'email' => 'kho.hcm@company.com',
                'capacity' => 5000.00,
                'status' => true,
                'description' => 'Kho trung tâm chính tại TP.HCM',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kho Phía Bắc',
                'code' => 'KH-HN-001',
                'location' => 'Hoàn Kiếm, Hà Nội',
                'address' => '456 Phố Tràng Tiền, Phường Tràng Tiền, Quận Hoàn Kiếm, Hà Nội',
                'manager' => 'Trần Thị B',
                'phone' => '0912345678',
                'email' => 'kho.hanoi@company.com',
                'capacity' => 3000.00,
                'status' => true,
                'description' => 'Kho chính phục vụ khu vực phía Bắc',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kho Miền Trung',
                'code' => 'KH-DN-001',
                'location' => 'Thanh Khê, Đà Nẵng',
                'address' => '789 Đường Lê Duẩn, Phường Thanh Khê Tây, Quận Thanh Khê, Đà Nẵng',
                'manager' => 'Lê Văn C',
                'phone' => '0923456789',
                'email' => 'kho.danang@company.com',
                'capacity' => 2500.00,
                'status' => true,
                'description' => 'Kho phục vụ khu vực miền Trung',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kho Thủ Đức',
                'code' => 'KH-TD-001',
                'location' => 'TP. Thủ Đức, TP. Hồ Chí Minh',
                'address' => '101 Đường Võ Văn Ngân, Phường Linh Chiểu, TP. Thủ Đức, TP. Hồ Chí Minh',
                'manager' => 'Phạm Thị D',
                'phone' => '0934567890',
                'email' => 'kho.thuduc@company.com',
                'capacity' => 4000.00,
                'status' => true,
                'description' => 'Kho phụ tại khu vực Thủ Đức',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kho Bình Dương',
                'code' => 'KH-BD-001',
                'location' => 'Thủ Dầu Một, Bình Dương',
                'address' => '202 Đường Đại lộ Bình Dương, Phường Phú Hòa, TP. Thủ Dầu Một, Bình Dương',
                'manager' => 'Hoàng Văn E',
                'phone' => '0945678901',
                'email' => 'kho.binhduong@company.com',
                'capacity' => 3500.00,
                'status' => true,
                'description' => 'Kho phụ tại Bình Dương',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($warehouses as $warehouse) {
            Warehouse::create($warehouse);
        }
    }
}
