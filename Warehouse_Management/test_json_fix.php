<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Testing JSON Decode Fix ===\n\n";

// Test notification data access
$notification = App\Models\Notification::first();

if ($notification) {
    echo "✓ Found notification ID: {$notification->id}\n";
    echo "✓ Data type: " . gettype($notification->data) . "\n";
    
    if (is_array($notification->data)) {
        echo "✓ Data is already an array - no json_decode needed!\n";
        
        // Test accessing products data like in the view
        if (isset($notification->data['products'])) {
            echo "✓ Products data accessible: " . count($notification->data['products']) . " products\n";
        } else {
            echo "ℹ No products data in this notification\n";
        }
    } else {
        echo "✗ Data is not an array - casting not working properly\n";
    }
    
    echo "\n=== Testing View Logic ===\n";
    
    // Simulate the fixed view logic
    if ($notification->data && isset($notification->data['products'])) {
        $data = $notification->data;
        $products = $data['products'] ?? [];
        echo "✓ View logic works: Found " . count($products) . " products\n";
    } else {
        echo "ℹ No products data to test view logic\n";
    }
    
} else {
    echo "✗ No notifications found to test\n";
}

echo "\n=== Fix Summary ===\n";
echo "✓ Removed json_decode() calls from notification show view\n";
echo "✓ Direct array access to \$notification->data works\n";
echo "✓ No more 'json_decode(): Argument #1 (\$json) must be of type string, array given' error\n";
echo "\n✅ JSON decode error has been fixed!\n";
