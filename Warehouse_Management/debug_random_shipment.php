<?php

require_once 'vendor/autoload.php';

// Initialize Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== DEBUGGING RANDOM SHIPMENT GENERATION ===\n\n";

// Check stores
$stores = \App\Models\Store::all();
echo "Available stores:\n";
foreach ($stores as $store) {
    echo "- ID: {$store->id}, Name: {$store->name}, Status: " . ($store->status ? 'Active' : 'Inactive') . "\n";
}

echo "\nChecking products...\n";
$products = \App\Models\Product::all();
echo "Total products: " . $products->count() . "\n";

if ($products->count() > 0) {
    echo "Sample products:\n";
    foreach ($products->take(3) as $product) {
        echo "- ID: {$product->id}, Name: {$product->name}, SKU: {$product->sku}, Price: {$product->price}\n";
    }
}

echo "\nChecking warehouses...\n";
$warehouses = \App\Models\Warehouse::all();
echo "Total warehouses: " . $warehouses->count() . "\n";

if ($warehouses->count() > 0) {
    echo "Sample warehouses:\n";
    foreach ($warehouses->take(3) as $warehouse) {
        echo "- ID: {$warehouse->id}, Name: {$warehouse->name}\n";
    }
}

echo "\nTesting manual shipment request creation...\n";

try {
    $store = $stores->first();
    $product = $products->first();
    $warehouse = $warehouses->first();
    
    if ($store && $product && $warehouse) {
        echo "Creating test notification...\n";
        
        $notification = \App\Models\Notification::create([
            'store_id' => $store->id,
            'title' => "Test Shipment Request",
            'message' => "Test message",
            'type' => 'receive_request',
            'status' => 'pending',
            'warehouse_id' => $warehouse->id,
            'data' => [
                'auto_generated' => true,
                'generation_type' => 'test_random_shipment',
                'test' => true
            ]
        ]);
        
        echo "✅ Successfully created test notification (ID: {$notification->id})\n";
        
        // Clean up
        $notification->delete();
        echo "✅ Cleaned up test notification\n";
        
    } else {
        echo "❌ Missing required data: Store: " . ($store ? 'OK' : 'Missing') . 
             ", Product: " . ($product ? 'OK' : 'Missing') . 
             ", Warehouse: " . ($warehouse ? 'OK' : 'Missing') . "\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Error creating test notification: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
}

echo "\n=== DEBUG COMPLETE ===\n";
