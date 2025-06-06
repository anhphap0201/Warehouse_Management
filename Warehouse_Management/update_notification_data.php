<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== UPDATING INCONSISTENT NOTIFICATION DATA ===\n\n";

use App\Models\Notification;

// Find notifications that have read_at but is_read is still false
$inconsistentNotifications = Notification::whereNotNull('read_at')
    ->where('is_read', false)
    ->get();

echo "Found {$inconsistentNotifications->count()} notifications with inconsistent read status\n";

if ($inconsistentNotifications->count() > 0) {
    echo "Updating inconsistent notifications...\n";
    
    foreach ($inconsistentNotifications as $notification) {
        echo "  Updating notification ID: {$notification->id} - {$notification->title}\n";
    }
    
    // Update all inconsistent notifications
    Notification::whereNotNull('read_at')
        ->where('is_read', false)
        ->update(['is_read' => true]);
    
    echo "✓ Updated {$inconsistentNotifications->count()} notifications\n";
    
    // Clear cache to reflect changes
    cache()->forget('unread_notifications_count');
    echo "✓ Cache cleared\n";
} else {
    echo "✓ No inconsistent notifications found\n";
}

echo "\n=== FINAL COUNT VERIFICATION ===\n";

// Re-check counts after update
$oldMethodCount = Notification::where('is_read', false)->count();
$newMethodCount = Notification::pending()->whereNull('read_at')->count();

echo "Old method count (is_read = false): {$oldMethodCount}\n";
echo "New method count (pending + read_at IS NULL): {$newMethodCount}\n";

if ($oldMethodCount === $newMethodCount) {
    echo "✓ All counting methods are now consistent!\n";
} else {
    echo "! Counts are still different, but this is expected since:\n";
    echo "  - Old method counts ALL unread notifications (including approved/rejected)\n";
    echo "  - New method counts only PENDING unread notifications\n";
    echo "  - This is the intended behavior to show only actionable notifications\n";
}

echo "\n=== COMPLETE ===\n";
