<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Đầu tiên đảm bảo các danh mục tồn tại
        $this->call(CategorySeeder::class);

        // Lấy các danh mục
        $electronics = Category::where('name', 'Điện tử')->first();
        $fashion = Category::where('name', 'Thời trang')->first();
        $household = Category::where('name', 'Gia dụng')->first();
        $food = Category::where('name', 'Thực phẩm')->first();
        $books = Category::where('name', 'Sách & Văn phòng phẩm')->first();
        $health = Category::where('name', 'Sức khỏe & Làm đẹp')->first();
        $sports = Category::where('name', 'Thể thao & Du lịch')->first();
        $baby = Category::where('name', 'Mẹ & Bé')->first();

        $products = [
            // Electronics
            [
                'name' => 'iPhone 15 Pro Max',
                'sku' => 'IP15PM-256',
                'category_id' => $electronics?->id,
                'unit' => 'Cái',
                'description' => 'iPhone 15 Pro Max 256GB Titan Tự Nhiên với chip A17 Pro mạnh mẽ',
            ],
            [
                'name' => 'Samsung Galaxy S24 Ultra',
                'sku' => 'SGS24U-512',
                'category_id' => $electronics?->id,
                'unit' => 'Cái',
                'description' => 'Samsung Galaxy S24 Ultra 512GB Phantom Black với S Pen tích hợp',
            ],
            [
                'name' => 'Laptop Dell XPS 13',
                'sku' => 'DELL-XPS13-I7',
                'category_id' => $electronics?->id,
                'unit' => 'Cái',
                'description' => 'Dell XPS 13 Intel i7 16GB RAM 512GB SSD màn hình 13.3 inch',
            ],
            [
                'name' => 'MacBook Air M2',
                'sku' => 'MBA-M2-512',
                'category_id' => $electronics?->id,
                'unit' => 'Cái',
                'description' => 'MacBook Air M2 512GB SSD 8GB RAM màn hình Liquid Retina',
            ],
            
            // Fashion
            [
                'name' => 'Áo Polo Nam',
                'sku' => 'POLO-M-001',
                'category_id' => $fashion?->id,
                'unit' => 'Cái',
                'description' => 'Áo Polo Nam Cotton 100% size M màu trắng phong cách lịch lãm',
            ],
            [
                'name' => 'Quần Jeans Nam',
                'sku' => 'JEANS-M-32',
                'category_id' => $fashion?->id,
                'unit' => 'Cái',
                'description' => 'Quần Jeans Nam size 32 chất liệu cotton co giãn thoải mái',
            ],
            [
                'name' => 'Giày thể thao Nike',
                'sku' => 'NIKE-AIR-42',
                'category_id' => $fashion?->id,
                'unit' => 'Đôi',
                'description' => 'Giày thể thao Nike Air Force 1 size 42 màu trắng classic',
            ],

            // Household
            [
                'name' => 'Nồi cơm điện Panasonic',
                'sku' => 'RICE-PAN-18L',
                'category_id' => $household?->id,
                'unit' => 'Cái',
                'description' => 'Nồi cơm điện Panasonic 1.8L SR-ZX185KRA với công nghệ kim cương',
            ],
            [
                'name' => 'Máy lọc nước RO',
                'sku' => 'WATER-RO-10L',
                'category_id' => $household?->id,
                'unit' => 'Cái',
                'description' => 'Máy lọc nước RO 10 cấp lọc công suất 10L/h loại bỏ 99% tạp chất',
            ],
            [
                'name' => 'Tủ lạnh Panasonic',
                'sku' => 'FRIDGE-PAN-350L',
                'category_id' => $household?->id,
                'unit' => 'Cái',
                'description' => 'Tủ lạnh Panasonic Inverter 350L tiết kiệm điện công nghệ Econavi',
            ],

            // Food
            [
                'name' => 'Gạo ST25',
                'sku' => 'RICE-ST25-5KG',
                'category_id' => $food?->id,
                'unit' => 'Kg',
                'description' => 'Gạo ST25 cao cấp túi 5kg thơm ngon đạt chuẩn xuất khẩu',
            ],
            [
                'name' => 'Dầu ăn Neptune',
                'sku' => 'OIL-NEP-1L',
                'category_id' => $food?->id,
                'unit' => 'Lít',
                'description' => 'Dầu ăn Neptune 1L từ đậu nành nguyên chất không cholesterol',
            ],
            [
                'name' => 'Sữa tươi Vinamilk',
                'sku' => 'MILK-VINA-1L',
                'category_id' => $food?->id,
                'unit' => 'Hộp',
                'description' => 'Sữa tươi tiệt trùng Vinamilk 1L giàu canxi và protein',
            ],

            // Books & Office
            [
                'name' => 'Sách "Đắc Nhân Tâm"',
                'sku' => 'BOOK-DNT-2024',
                'category_id' => $books?->id,
                'unit' => 'Cuốn',
                'description' => 'Sách Đắc Nhân Tâm - Dale Carnegie bản dịch mới nhất 2024',
            ],
            [
                'name' => 'Bút bi Thiên Long',
                'sku' => 'PEN-TL-BLUE',
                'category_id' => $books?->id,
                'unit' => 'Cái',
                'description' => 'Bút bi Thiên Long TL-027 màu xanh mực viết mượt không lem',
            ],
            [
                'name' => 'Vở học sinh',
                'sku' => 'NOTEBOOK-200P',
                'category_id' => $books?->id,
                'unit' => 'Cuốn',
                'description' => 'Vở học sinh 200 trang ô ly chất lượng cao an toàn cho trẻ em',
            ],

            // Health & Beauty
            [
                'name' => 'Kem chống nắng Nivea',
                'sku' => 'SUN-NIVEA-50ML',
                'category_id' => $health?->id,
                'unit' => 'Tuýp',
                'description' => 'Kem chống nắng Nivea SPF 50+ 50ml bảo vệ da khỏi tia UV',
            ],
            [
                'name' => 'Vitamin C 1000mg',
                'sku' => 'VIT-C-1000',
                'category_id' => $health?->id,
                'unit' => 'Hộp',
                'description' => 'Vitamin C 1000mg tăng cường sức đề kháng hộp 30 viên',
            ],

            // Sports & Travel
            [
                'name' => 'Bóng đá FIFA Quality',
                'sku' => 'BALL-FIFA-5',
                'category_id' => $sports?->id,
                'unit' => 'Quả',
                'description' => 'Bóng đá số 5 FIFA Quality Pro chất liệu PU cao cấp',
            ],
            [
                'name' => 'Balo du lịch 40L',
                'sku' => 'BACKPACK-40L',
                'category_id' => $sports?->id,
                'unit' => 'Cái',
                'description' => 'Balo du lịch 40L chống nước có nhiều ngăn tiện dụng',
            ],

            // Baby & Mother
            [
                'name' => 'Sữa bột Enfamil',
                'sku' => 'MILK-ENF-900G',
                'category_id' => $baby?->id,
                'unit' => 'Hộp',
                'description' => 'Sữa bột Enfamil A+ số 1 900g cho trẻ 0-6 tháng tuổi',
            ],
            [
                'name' => 'Tã em bé Pampers',
                'sku' => 'DIAPER-PAM-M',
                'category_id' => $baby?->id,
                'unit' => 'Gói',
                'description' => 'Tã em bé Pampers Baby Dry size M siêu thấm khô thoáng 64 miếng',
            ],
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(['sku' => $product['sku']], $product);
        }
    }
}
