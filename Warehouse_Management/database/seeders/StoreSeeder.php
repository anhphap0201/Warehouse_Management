<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Store;

class StoreSeeder extends Seeder
{    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing store data
        Store::truncate();

        // Create main flagship stores (guaranteed active)
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

        // Create regional stores in major cities
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

        // Create stores in other cities
        $otherCities = ['Nha Trang', 'Vũng Tàu', 'Đà Lạt', 'Huế', 'Quy Nhon'];
        foreach ($otherCities as $city) {
            Store::factory()
                ->count(rand(1, 2))
                ->inCity($city)
                ->create();
        }

        // Create some inactive stores for testing
        Store::factory()
            ->count(3)
            ->inactive()
            ->create();

        // Create stores with specific types
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

        // Display summary
        $totalStores = Store::count();
        $activeStores = Store::where('status', true)->count();
        $inactiveStores = Store::where('status', false)->count();
        
        $this->command->info("Created {$totalStores} stores:");
        $this->command->info("- Active: {$activeStores}");
        $this->command->info("- Inactive: {$inactiveStores}");
        
        // Show stores by city
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
