<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Testing Notification System ===\n\n";

// Check stores
$storeCount = App\Models\Store::count();
echo "Total stores: $storeCount\n";

if ($storeCount > 0) {
    $stores = App\Models\Store::take(3)->get(['id', 'name']);
    echo "Sample stores:\n";
    foreach ($stores as $store) {
        echo "- ID: {$store->id}, Name: {$store->name}\n";
    }
    
    // Create test notifications
    echo "\n=== Creating Test Notifications ===\n";
    
    $firstStore = $stores->first();
    
    // Create a receive request notification
    $receiveNotification = App\Models\Notification::create([
        'store_id' => $firstStore->id,
        'type' => 'receive_request',
        'title' => 'Yêu cầu nhận hàng từ ' . $firstStore->name,
        'message' => 'Cửa hàng ' . $firstStore->name . ' yêu cầu nhận hàng từ kho.',
        'status' => 'pending'
    ]);
    
    echo "✓ Created receive request notification (ID: {$receiveNotification->id})\n";
    
    // Create a return request notification
    $returnNotification = App\Models\Notification::create([
        'store_id' => $firstStore->id,
        'type' => 'return_request',
        'title' => 'Yêu cầu trả hàng từ ' . $firstStore->name,
        'message' => 'Cửa hàng ' . $firstStore->name . ' yêu cầu trả hàng về kho.',
        'status' => 'pending'
    ]);
    
    echo "✓ Created return request notification (ID: {$returnNotification->id})\n";
    
} else {
    echo "No stores found. Creating a test store...\n";
    
    $testStore = App\Models\Store::create([
        'name' => 'Test Store',
        'address' => '123 Test Street',
        'phone' => '0123456789'
    ]);
    
    echo "✓ Created test store (ID: {$testStore->id})\n";
    
    // Create test notifications for the new store
    $receiveNotification = App\Models\Notification::create([
        'store_id' => $testStore->id,
        'type' => 'receive_request',
        'title' => 'Yêu cầu nhận hàng từ ' . $testStore->name,
        'message' => 'Cửa hàng ' . $testStore->name . ' yêu cầu nhận hàng từ kho.',
        'status' => 'pending'
    ]);
    
    echo "✓ Created receive request notification (ID: {$receiveNotification->id})\n";
}

// Check notification count
$notificationCount = App\Models\Notification::count();
$pendingCount = App\Models\Notification::where('status', 'pending')->count();

echo "\n=== Notification Summary ===\n";
echo "Total notifications: $notificationCount\n";
echo "Pending notifications: $pendingCount\n";

echo "\n=== Test completed successfully! ===\n";
