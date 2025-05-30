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
                'location' => 'Quận 1, TP. Hồ Chí Minh',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kho Phía Bắc',
                'location' => 'Hoàn Kiếm, Hà Nội',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kho Miền Trung',
                'location' => 'Thanh Khê, Đà Nẵng',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kho Thủ Đức',
                'location' => 'TP. Thủ Đức, TP. Hồ Chí Minh',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kho Bình Dương',
                'location' => 'Thủ Dầu Một, Bình Dương',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($warehouses as $warehouse) {
            Warehouse::create($warehouse);
        }
    }
}
