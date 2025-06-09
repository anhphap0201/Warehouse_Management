<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Store;

class StoreSeeder extends Seeder
{    /**
     * Run the database seeds.
     */
    public function run(): void
    {        // Xóa dữ liệu cửa hàng hiện có
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Store::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Tạo các cửa hàng chủ lực chính (đảm bảo hoạt động)
        Store::factory()
            ->active()
            ->inCity('TP. Hồ Chí Minh')
            ->type('Flagship Store')
            ->create([
                'name' => 'Flagship Store Quận 1',
                'manager' => 'Nguyễn Văn An'
            ]);

        Store::factory()
            ->active()
            ->inCity('Hà Nội')
            ->type('Flagship Store')
            ->create([
                'name' => 'Flagship Store Hà Nội',
                'manager' => 'Trần Thị Bình'
            ]);

        // Tạo các cửa hàng khu vực tại các thành phố lớn
        $majorCities = [
            'TP. Hồ Chí Minh' => 8,
            'Hà Nội' => 6,
            'Đà Nẵng' => 3,
            'Hải Phòng' => 2,
            'Cần Thơ' => 2,
        ];

        foreach ($majorCities as $city => $count) {
            Store::factory()
                ->count($count)
                ->inCity($city)
                ->create();
        }

        // Tạo cửa hàng tại các thành phố khác
        $otherCities = ['Nha Trang', 'Vũng Tàu', 'Đà Lạt', 'Huế', 'Quy Nhon'];
        foreach ($otherCities as $city) {
            Store::factory()
                ->count(rand(1, 2))
                ->inCity($city)
                ->create();
        }

        // Tạo một số cửa hàng ngừng hoạt động để thử nghiệm
        Store::factory()
            ->count(3)
            ->inactive()
            ->create();

        // Tạo cửa hàng với loại hình cụ thể
        Store::factory()
            ->count(2)
            ->type('Showroom')
            ->active()
            ->create();

        Store::factory()
            ->count(2)
            ->type('Kho hàng')
            ->active()
            ->create();

        // Hiển thị tóm tắt
        $totalStores = Store::count();
        $activeStores = Store::where('status', true)->count();
        $inactiveStores = Store::where('status', false)->count();
        
        $this->command->info("Created {$totalStores} stores:");
        $this->command->info("- Active: {$activeStores}");
        $this->command->info("- Inactive: {$inactiveStores}");
        
        // Hiển thị cửa hàng theo thành phố
        $storesByCity = Store::selectRaw('
            CASE 
                WHEN location LIKE "%TP. Hồ Chí Minh%" THEN "TP. Hồ Chí Minh"
                WHEN location LIKE "%Hà Nội%" THEN "Hà Nội"
                WHEN location LIKE "%Đà Nẵng%" THEN "Đà Nẵng"
                WHEN location LIKE "%Hải Phòng%" THEN "Hải Phòng"
                WHEN location LIKE "%Cần Thơ%" THEN "Cần Thơ"
                ELSE "Khác"
            END as city,
            COUNT(*) as count
        ')
        ->groupBy('city')
        ->orderBy('count', 'desc')
        ->get();

        $this->command->info("\nStores by city:");
        foreach ($storesByCity as $cityData) {
            $this->command->info("- {$cityData->city}: {$cityData->count} stores");
        }
    }
}
