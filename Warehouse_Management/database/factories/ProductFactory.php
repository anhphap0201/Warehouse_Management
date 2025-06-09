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
        
        // Generate price based on product type
        $price = match(true) {
            str_contains($productName, 'iPhone') || str_contains($productName, 'Samsung') => $this->faker->numberBetween(15000000, 35000000),
            str_contains($productName, 'Laptop') || str_contains($productName, 'MacBook') => $this->faker->numberBetween(20000000, 50000000),
            str_contains($productName, 'iPad') || str_contains($productName, 'Đồng hồ') => $this->faker->numberBetween(8000000, 25000000),
            str_contains($productName, 'Áo') || str_contains($productName, 'Quần') => $this->faker->numberBetween(200000, 1500000),
            str_contains($productName, 'Giày') || str_contains($productName, 'Túi') => $this->faker->numberBetween(500000, 3000000),
            str_contains($productName, 'Tủ lạnh') || str_contains($productName, 'Máy giặt') => $this->faker->numberBetween(8000000, 20000000),
            str_contains($productName, 'Điều hòa') => $this->faker->numberBetween(6000000, 15000000),
            str_contains($productName, 'Nồi') || str_contains($productName, 'Máy lọc') => $this->faker->numberBetween(1000000, 5000000),
            default => $this->faker->numberBetween(50000, 2000000)
        };

        // Generate brands
        $brands = [
            'Apple', 'Samsung', 'Dell', 'LG', 'Panasonic', 'Sony', 'Nike', 'Adidas', 
            'Uniqlo', 'Zara', 'Vinamilk', 'Neptune', 'Thiên Long', 'Canon', 'HP'
        ];

        // Generate barcode
        $barcode = $this->faker->ean13();

        // Generate status
        $status = $this->faker->randomElement(['active', 'inactive', 'out_of_stock']);

        // Generate stock levels
        $minStock = $this->faker->numberBetween(5, 20);
        $maxStock = $this->faker->numberBetween(50, 200);

        // Generate attributes based on product type
        $attributes = [];
        if (str_contains($productName, 'iPhone') || str_contains($productName, 'Samsung')) {
            $attributes = [
                'color' => $this->faker->randomElement(['Black', 'White', 'Blue', 'Gold', 'Purple']),
                'storage' => $this->faker->randomElement(['128GB', '256GB', '512GB', '1TB']),
                'warranty' => '12 months'
            ];
        } elseif (str_contains($productName, 'Áo') || str_contains($productName, 'Quần')) {
            $attributes = [
                'size' => $this->faker->randomElement(['S', 'M', 'L', 'XL', 'XXL']),
                'color' => $this->faker->randomElement(['Đen', 'Trắng', 'Xanh', 'Đỏ', 'Vàng']),
                'material' => $this->faker->randomElement(['Cotton', 'Polyester', 'Denim', 'Silk'])
            ];
        } elseif (str_contains($productName, 'Tủ lạnh') || str_contains($productName, 'Máy giặt')) {
            $attributes = [
                'capacity' => $this->faker->randomElement(['200L', '300L', '400L', '500L']),
                'energy_rating' => $this->faker->randomElement(['3 stars', '4 stars', '5 stars']),
                'warranty' => '24 months'
            ];
        }

        return [
            'name' => $productName,
            'sku' => $sku,
            'category_id' => Category::factory(),
            'unit' => $this->faker->randomElement($units),
            'description' => $this->faker->sentence(10),
            'price' => $price,
            'brand' => $this->faker->randomElement($brands),
            'barcode' => $barcode,
            'min_stock_level' => $minStock,
            'max_stock_level' => $maxStock,
            'status' => $status,
            'image' => 'products/' . $this->faker->word . '.jpg',
            'attributes' => !empty($attributes) ? json_encode($attributes) : null,
        ];
    }
}
