<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create predefined categories
        $categories = [
            ['name' => 'Điện tử'],
            ['name' => 'Thời trang'],
            ['name' => 'Gia dụng'],
            ['name' => 'Thực phẩm'],
            ['name' => 'Sách & Văn phòng phẩm'],
            ['name' => 'Sức khỏe & Làm đẹp'],
            ['name' => 'Thể thao & Du lịch'],
            ['name' => 'Mẹ & Bé'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate($category);
        }

        $this->command->info('Created ' . count($categories) . ' categories.');
    }
}
