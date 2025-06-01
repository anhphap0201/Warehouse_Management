<?php

namespace Database\Factories;

use App\Models\Category;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $products = [
            'iPhone 15 Pro Max',
            'Samsung Galaxy S24',
            'Laptop Dell XPS 13',
            'MacBook Air M2',
            'iPad Pro 12.9',
            'Áo thun cotton',
            'Quần jeans nam',
            'Giày thể thao Nike',
            'Túi xách nữ',
            'Đồng hồ thông minh',
            'Nồi cơm điện',
            'Máy lọc nước',
            'Tủ lạnh Panasonic',
            'Máy giặt LG',
            'Điều hòa Daikin',
            'Gạo ST25',
            'Dầu ăn Neptune',
            'Sữa tươi Vinamilk',
            'Thịt bò Úc',
            'Cá hồi Na Uy',
            'Sách lập trình',
            'Bút bi Thiên Long',
            'Vở học sinh',
            'Máy in Canon',
            'Kem chống nắng',
        ];

        $units = ['Cái', 'Kg', 'Lít', 'Gói', 'Hộp', 'Túi', 'Cuốn', 'Chiếc', 'Bộ', 'Thùng'];
        
        $productName = $this->faker->randomElement($products);
        $sku = strtoupper($this->faker->bothify('???-###'));

        return [
            'name' => $productName,
            'sku' => $sku,
            'category_id' => Category::factory(),
            'unit' => $this->faker->randomElement($units),
            'description' => $this->faker->sentence(10),
        ];
    }
}
