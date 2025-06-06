<?php

require_once 'vendor/autoload.php';

// Initialize Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== TESTING SHIPMENT REQUEST GENERATION MANUALLY ===\n\n";

// Get a store
$store = \App\Models\Store::first();
echo "Testing with store: {$store->name} (ID: {$store->id})\n";

// Get available products (those that exist in the system)
echo "Getting available products...\n";
$availableProducts = \App\Models\Product::whereHas('storeInventories', function($query) use ($store) {
    $query->where('store_id', $store->id);
})->get();

echo "Products with store inventory: " . $availableProducts->count() . "\n";

if ($availableProducts->isEmpty()) {
    echo "No store inventory found, getting any products...\n";
    $availableProducts = \App\Models\Product::take(10)->get();
    echo "Any products: " . $availableProducts->count() . "\n";
}

if ($availableProducts->isEmpty()) {
    echo "No products found at all!\n";
    exit(1);
}

echo "Selected products:\n";
foreach ($availableProducts->take(3) as $product) {
    echo "- ID: {$product->id}, Name: {$product->name}, Price: " . ($product->price ?? 'NULL') . "\n";
}

// Check if products have prices
$productsWithPrices = $availableProducts->filter(function($product) {
    return $product->price !== null && $product->price > 0;
});

echo "\nProducts with valid prices: " . $productsWithPrices->count() . "\n";

if ($productsWithPrices->isEmpty()) {
    echo "No products have valid prices! This might be the issue.\n";
    
    // Check the actual price values
    echo "Checking raw price data:\n";
    $products = \App\Models\Product::take(5)->get(['id', 'name', 'price']);
    foreach ($products as $product) {
        echo "- ID: {$product->id}, Name: {$product->name}, Price: " . var_export($product->price, true) . "\n";
    }
}

// Test creating a basic notification manually
try {
    echo "\nTesting notification creation...\n";
    
    $product = $availableProducts->first();
    $warehouse = \App\Models\Warehouse::first();
    
    $notification = \App\Models\Notification::create([
        'store_id' => $store->id,
        'title' => "Manual Test Shipment Request",
        'message' => "Manual test",
        'type' => 'receive_request',
        'status' => 'pending',
        'warehouse_id' => $warehouse->id,
        'data' => [
            'products' => [[
                'product_id' => $product->id,
                'product_name' => $product->name,
                'quantity' => 10,
                'unit_price' => $product->price ?? 0,
                'total_price' => ($product->price ?? 0) * 10,
            ]],
            'auto_generated' => true,
            'generation_type' => 'test_manual',
        ]
    ]);
    
    echo "✅ Successfully created notification (ID: {$notification->id})\n";
    
    // Clean up
    $notification->delete();
    echo "✅ Cleaned up test notification\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== TEST COMPLETE ===\n";
