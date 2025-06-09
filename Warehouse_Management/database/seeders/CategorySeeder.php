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
        // Tạo các danh mục được định trước
        $categories = [
            [
                'name' => 'Điện tử',
                'slug' => 'dien-tu',
                'description' => 'Các sản phẩm điện tử và công nghệ',
                'parent_id' => null,
            ],
            [
                'name' => 'Thời trang',
                'slug' => 'thoi-trang',
                'description' => 'Quần áo, giày dép, phụ kiện thời trang',
                'parent_id' => null,
            ],
            [
                'name' => 'Gia dụng',
                'slug' => 'gia-dung',
                'description' => 'Đồ dùng trong gia đình',
                'parent_id' => null,
            ],
            [
                'name' => 'Thực phẩm',
                'slug' => 'thuc-pham',
                'description' => 'Thực phẩm và đồ uống',
                'parent_id' => null,
            ],
            [
                'name' => 'Sách & Văn phòng phẩm',
                'slug' => 'sach-van-phong-pham',
                'description' => 'Sách và các dụng cụ văn phòng',
                'parent_id' => null,
            ],
            [
                'name' => 'Sức khỏe & Làm đẹp',
                'slug' => 'suc-khoe-lam-dep',
                'description' => 'Sản phẩm chăm sóc sức khỏe và làm đẹp',
                'parent_id' => null,
            ],
            [
                'name' => 'Thể thao & Du lịch',
                'slug' => 'the-thao-du-lich',
                'description' => 'Dụng cụ thể thao và du lịch',
                'parent_id' => null,
            ],
            [
                'name' => 'Mẹ & Bé',
                'slug' => 'me-be',
                'description' => 'Sản phẩm cho mẹ và bé',
                'parent_id' => null,
            ],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate($category);
        }

        $this->command->info('Created ' . count($categories) . ' categories.');
    }
}
