<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Testing Notification Creation Fix ===\n\n";

try {
    $store = App\Models\Store::where('status', true)->first();
    if (!$store) {
        echo "❌ No active stores found\n";
        exit(1);
    }
    
    echo "✓ Found active store: {$store->name}\n";
    
    // Test notification creation without created_by field
    $notification = App\Models\Notification::create([
        'store_id' => $store->id,
        'type' => 'return_request',
        'title' => 'Test notification (fix verification)',
        'message' => 'Testing that notifications can be created without created_by field',
        'status' => 'pending',
        'data' => [
            'test' => true,
            'fix_verification' => 'created_by field removed'
        ]
    ]);
    
    echo "✅ SUCCESS: Notification created with ID: {$notification->id}\n";
    echo "✓ No SQL errors - created_by field successfully removed\n";
    
    // Clean up test data
    $notification->delete();
    echo "✓ Test cleanup completed\n\n";
    
    echo "=== Fix Verification Complete ===\n";
    echo "✅ The created_by column error has been resolved!\n";
    echo "✅ Auto-generation controller should now work correctly\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "❌ The fix needs additional work\n";
    exit(1);
}
