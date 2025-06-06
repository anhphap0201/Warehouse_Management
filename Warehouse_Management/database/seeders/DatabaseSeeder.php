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
        // Create test user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Seed in correct order to maintain foreign key relationships
        $this->call([
            // Basic entities first
            WarehouseSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            StoreSeeder::class,
            SupplierSeeder::class,
            
            // Then inventory-related data
            InventorySeeder::class,
            StoreInventorySeeder::class,
        ]);
    }
}
