<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Tạo người dùng thử nghiệm
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Tạo dữ liệu theo thứ tự đúng để duy trì mối quan hệ khóa ngoại
        $this->call([
            // Các thực thể cơ bản trước
            WarehouseSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            StoreSeeder::class,
            SupplierSeeder::class,
            
            // Sau đó là dữ liệu liên quan đến tồn kho
            InventorySeeder::class,
            StoreInventorySeeder::class,
        ]);
    }
}
