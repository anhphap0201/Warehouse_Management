<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = [
            'Điện tử' => 'Các sản phẩm điện tử như điện thoại, laptop, máy tính bảng',
            'Thời trang' => 'Quần áo, giày dép, phụ kiện thời trang',
            'Gia dụng' => 'Đồ gia dụng, nội thất, thiết bị nhà bếp',
            'Thực phẩm' => 'Thực phẩm tươi sống, đồ khô, đồ uống',
            'Sách & Văn phòng phẩm' => 'Sách, vở, bút viết, đồ dùng văn phòng',
            'Sức khỏe & Làm đẹp' => 'Thuốc, mỹ phẩm, thiết bị chăm sóc sức khỏe',
            'Thể thao & Du lịch' => 'Dụng cụ thể thao, đồ du lịch, camping',
            'Mẹ & Bé' => 'Đồ dùng cho mẹ và bé, đồ chơi trẻ em',
        ];

        $category = $this->faker->randomElement(array_keys($categories));
        
        return [
            'name' => $category,
            'description' => $categories[$category],
        ];
    }
}
