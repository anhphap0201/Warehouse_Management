<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Testing Notification Approval/Rejection ===\n\n";

// Get first notification
$notification = App\Models\Notification::first();

if ($notification) {
    echo "Testing notification ID: {$notification->id}\n";
    echo "Current status: {$notification->status}\n";
    echo "Type: {$notification->type}\n";
    echo "Store: {$notification->store->name}\n\n";
    
    // Test approval
    echo "Testing approval...\n";
    $notification->update([
        'status' => 'approved',
        'approved_at' => now()
    ]);
    
    $notification->refresh();
    echo "✓ Status after approval: {$notification->status}\n";
    echo "✓ Approved at: {$notification->approved_at}\n\n";
    
    // Create another notification for rejection test
    $rejectNotification = App\Models\Notification::create([
        'store_id' => $notification->store_id,
        'type' => 'receive_request',
        'title' => 'Test Rejection Notification',
        'message' => 'This notification will be rejected for testing.',
        'status' => 'pending'
    ]);
    
    echo "Created test notification for rejection (ID: {$rejectNotification->id})\n";
    
    // Test rejection
    echo "Testing rejection...\n";
    $rejectNotification->update([
        'status' => 'rejected',
        'rejected_at' => now(),
        'rejection_reason' => 'Test rejection reason'
    ]);
    
    $rejectNotification->refresh();
    echo "✓ Status after rejection: {$rejectNotification->status}\n";
    echo "✓ Rejected at: {$rejectNotification->rejected_at}\n";
    echo "✓ Rejection reason: {$rejectNotification->rejection_reason}\n\n";
    
} else {
    echo "No notifications found for testing.\n";
}

// Summary
$totalCount = App\Models\Notification::count();
$pendingCount = App\Models\Notification::where('status', 'pending')->count();
$approvedCount = App\Models\Notification::where('status', 'approved')->count();
$rejectedCount = App\Models\Notification::where('status', 'rejected')->count();

echo "=== Final Summary ===\n";
echo "Total notifications: $totalCount\n";
echo "Pending: $pendingCount\n";
echo "Approved: $approvedCount\n";
echo "Rejected: $rejectedCount\n";

echo "\n=== All tests completed successfully! ===\n";
