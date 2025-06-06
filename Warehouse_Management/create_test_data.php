<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;
use App\Models\Store;
use App\Models\StoreInventory;

echo "Creating test store inventory data...\n";

$store = Store::first();
if (!$store) {
    echo "No store found. Please create a store first.\n";
    exit(1);
}

$products = Product::all();
if ($products->isEmpty()) {
    echo "No products found. Please create products first.\n";
    exit(1);
}

foreach ($products as $product) {
    $existing = StoreInventory::where('store_id', $store->id)
                             ->where('product_id', $product->id)
                             ->first();
    
    if (!$existing) {
        StoreInventory::create([
            'store_id' => $store->id,
            'product_id' => $product->id,
            'quantity' => rand(50, 200),
            'last_updated' => now()
        ]);
        echo "Created inventory for product: {$product->name}\n";
    } else {
        echo "Inventory already exists for product: {$product->name}\n";
    }
}

echo "Test data creation completed!\n";
echo "Total store inventories: " . StoreInventory::count() . "\n";
