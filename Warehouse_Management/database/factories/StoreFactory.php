<?php

namespace Database\Factories;

use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Store>
 */
class StoreFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Store::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $storeTypes = ['Cửa hàng', 'Chi nhánh', 'Showroom', 'Đại lý', 'Siêu thị mini'];
        $cities = [
            'TP. Hồ Chí Minh' => ['Quận 1', 'Quận 2', 'Quận 3', 'Quận 4', 'Quận 5', 'Quận 7', 'Quận 10', 'Bình Thạnh', 'Tân Bình', 'Phú Nhuận', 'Gò Vấp', 'Thủ Đức'],
            'Hà Nội' => ['Hoàn Kiếm', 'Ba Đình', 'Đống Đa', 'Hai Bà Trưng', 'Cầu Giấy', 'Thanh Xuân', 'Long Biên', 'Nam Từ Liêm', 'Bắc Từ Liêm'],
            'Đà Nẵng' => ['Hải Châu', 'Thanh Khê', 'Sơn Trà', 'Ngũ Hành Sơn', 'Liên Chiểu', 'Cẩm Lệ'],
            'Hải Phòng' => ['Hồng Bàng', 'Ngô Quyền', 'Lê Chân', 'Kiến An', 'Đồ Sơn'],
            'Cần Thơ' => ['Ninh Kiều', 'Bình Thủy', 'Cái Răng', 'Ô Môn', 'Thốt Nốt'],
            'Bình Dương' => ['Thủ Dầu Một', 'Thuận An', 'Dĩ An', 'Tân Uyên', 'Bến Cát'],
            'Đồng Nai' => ['Biên Hòa', 'Long Thành', 'Nhơn Trạch', 'Vĩnh Cửu'],
            'Vũng Tàu' => ['Vũng Tàu', 'Bà Rịa', 'Long Điền', 'Đất Đỏ'],
            'Nha Trang' => ['Nha Trang', 'Cam Ranh', 'Ninh Hòa'],
            'Huế' => ['Huế', 'Phong Điền', 'Hương Thủy']
        ];

        $cityName = $this->faker->randomElement(array_keys($cities));
        $district = $this->faker->randomElement($cities[$cityName]);
        $storeType = $this->faker->randomElement($storeTypes);

        // Tạo tên quản lý ngẫu nhiên
        $firstNames = ['Nguyễn', 'Trần', 'Lê', 'Phạm', 'Huỳnh', 'Hoàng', 'Phan', 'Vũ', 'Võ', 'Đặng', 'Bùi', 'Đỗ', 'Hồ', 'Ngô', 'Dương'];
        $middleNames = ['Văn', 'Thị', 'Hoàng', 'Minh', 'Công', 'Thanh', 'Quốc', 'Hữu', 'Tuấn', 'Xuân'];
        $lastNames = ['An', 'Bình', 'Cường', 'Dung', 'Em', 'Phong', 'Giang', 'Hà', 'Khánh', 'Linh', 'Mai', 'Nam', 'Oanh', 'Phúc', 'Quý', 'Sơn', 'Tâm', 'Uyên', 'Vân', 'Yến'];

        $managerName = $this->faker->randomElement($firstNames) . ' ' . 
                      $this->faker->randomElement($middleNames) . ' ' . 
                      $this->faker->randomElement($lastNames);

        return [
            'name' => $storeType . ' ' . $district,
            'location' => $district . ', ' . $cityName,
            'phone' => '09' . $this->faker->numberBetween(10000000, 99999999),
            'manager' => $managerName,
            'status' => $this->faker->boolean(85), // 85% cửa hàng hoạt động
            'created_at' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'updated_at' => function (array $attributes) {
                return $this->faker->dateTimeBetween($attributes['created_at'], 'now');
            },
        ];
    }

    /**
     * Indicate that the store is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => true,
        ]);
    }

    /**
     * Indicate that the store is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => false,
        ]);
    }

    /**
     * Create a store in a specific city.
     */
    public function inCity(string $cityName): static
    {
        $cities = [
            'TP. Hồ Chí Minh' => ['Quận 1', 'Quận 2', 'Quận 3', 'Quận 4', 'Quận 5', 'Quận 7', 'Quận 10', 'Bình Thạnh', 'Tân Bình', 'Phú Nhuận'],
            'Hà Nội' => ['Hoàn Kiếm', 'Ba Đình', 'Đống Đa', 'Hai Bà Trưng', 'Cầu Giấy', 'Thanh Xuân'],
            'Đà Nẵng' => ['Hải Châu', 'Thanh Khê', 'Sơn Trà', 'Ngũ Hành Sơn'],
        ];

        $districts = $cities[$cityName] ?? ['Trung tâm'];
        $district = $this->faker->randomElement($districts);

        return $this->state(fn (array $attributes) => [
            'location' => $district . ', ' . $cityName,
        ]);
    }

    /**
     * Create a store with a specific type.
     */
    public function type(string $type): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => $type . ' ' . explode(',', $attributes['location'])[0],
        ]);
    }
}
