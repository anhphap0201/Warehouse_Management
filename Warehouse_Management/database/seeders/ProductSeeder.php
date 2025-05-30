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
        // Tạo categories trước
        $categories = [
            ['name' => 'Điện tử'],
            ['name' => 'Thời trang'],
            ['name' => 'Gia dụng'],
            ['name' => 'Thực phẩm'],
            ['name' => 'Sách & Văn phòng phẩm'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate($category);
        }

        // Lấy categories đã tạo
        $electronics = Category::where('name', 'Điện tử')->first();
        $fashion = Category::where('name', 'Thời trang')->first();
        $household = Category::where('name', 'Gia dụng')->first();
        $food = Category::where('name', 'Thực phẩm')->first();
        $books = Category::where('name', 'Sách & Văn phòng phẩm')->first();

        $products = [
            [
                'name' => 'iPhone 15 Pro Max',
                'sku' => 'IP15PM-256',
                'category_id' => $electronics?->id,
                'unit' => 'Cái',
                'description' => 'iPhone 15 Pro Max 256GB Titan Tự Nhiên',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Samsung Galaxy S24 Ultra',
                'sku' => 'SGS24U-512',
                'category_id' => $electronics?->id,
                'unit' => 'Cái',
                'description' => 'Samsung Galaxy S24 Ultra 512GB Phantom Black',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Áo Polo Nam',
                'sku' => 'POLO-M-001',
                'category_id' => $fashion?->id,
                'unit' => 'Cái',
                'description' => 'Áo Polo Nam Cotton 100% size M',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Nồi cơm điện Panasonic',
                'sku' => 'RICE-PAN-18L',
                'category_id' => $household?->id,
                'unit' => 'Cái',
                'description' => 'Nồi cơm điện Panasonic 1.8L SR-ZX185KRA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gạo ST25',
                'sku' => 'RICE-ST25-5KG',
                'category_id' => $food?->id,
                'unit' => 'Kg',
                'description' => 'Gạo ST25 cao cấp túi 5kg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Laptop Dell XPS 13',
                'sku' => 'DELL-XPS13-I7',
                'category_id' => $electronics?->id,
                'unit' => 'Cái',
                'description' => 'Dell XPS 13 Intel i7 16GB RAM 512GB SSD',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sách "Đắc Nhân Tâm"',
                'sku' => 'BOOK-DNT-2024',
                'category_id' => $books?->id,
                'unit' => 'Cuốn',
                'description' => 'Sách Đắc Nhân Tâm - Dale Carnegie',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Máy lọc nước RO',
                'sku' => 'WATER-RO-10L',
                'category_id' => $household?->id,
                'unit' => 'Cái',
                'description' => 'Máy lọc nước RO 10 cấp lọc',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(['sku' => $product['sku']], $product);
        }
    }
}
