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
            [
                'name' => 'Điện tử',
                'description' => 'Các sản phẩm điện tử như điện thoại, laptop, máy tính bảng, thiết bị âm thanh'
            ],
            [
                'name' => 'Thời trang',
                'description' => 'Quần áo, giày dép, phụ kiện thời trang cho nam, nữ và trẻ em'
            ],
            [
                'name' => 'Gia dụng',
                'description' => 'Đồ gia dụng, nội thất, thiết bị nhà bếp, dụng cụ sinh hoạt'
            ],
            [
                'name' => 'Thực phẩm',
                'description' => 'Thực phẩm tươi sống, đồ khô, đồ uống, gia vị và nguyên liệu nấu ăn'
            ],
            [
                'name' => 'Sách & Văn phòng phẩm',
                'description' => 'Sách, vở, bút viết, đồ dùng văn phòng, thiết bị học tập'
            ],
            [
                'name' => 'Sức khỏe & Làm đẹp',
                'description' => 'Thuốc, mỹ phẩm, thiết bị chăm sóc sức khỏe, sản phẩm làm đẹp'
            ],
            [
                'name' => 'Thể thao & Du lịch',
                'description' => 'Dụng cụ thể thao, đồ du lịch, camping, thiết bị outdoor'
            ],
            [
                'name' => 'Mẹ & Bé',
                'description' => 'Đồ dùng cho mẹ và bé, đồ chơi trẻ em, sữa bột, tã em bé'
            ],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['name' => $category['name']],
                $category
            );
        }
    }
}
