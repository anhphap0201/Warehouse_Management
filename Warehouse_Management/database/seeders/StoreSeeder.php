<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Store;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stores = [
            [
                'name' => 'Cửa hàng Quận 1',
                'location' => 'Quận 1, TP. Hồ Chí Minh',
                'phone' => '0901234567',
                'manager' => 'Nguyễn Văn An',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Chi nhánh Hà Nội',
                'location' => 'Hoàn Kiếm, Hà Nội',
                'phone' => '0902345678',
                'manager' => 'Trần Thị Bình',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cửa hàng Đà Nẵng',
                'location' => 'Hải Châu, Đà Nẵng',
                'phone' => '0903456789',
                'manager' => 'Lê Văn Cường',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Chi nhánh Bình Thạnh',
                'location' => 'Bình Thạnh, TP. Hồ Chí Minh',
                'phone' => '0904567890',
                'manager' => 'Phạm Thị Dung',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cửa hàng Cần Thơ',
                'location' => 'Ninh Kiều, Cần Thơ',
                'phone' => '0905678901',
                'manager' => 'Huỳnh Văn Em',
                'status' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($stores as $store) {
            Store::create($store);
        }
    }
}
