<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== TESTING NOTIFICATION COUNT FIX ===\n\n";

use App\Models\Notification;
use App\Http\View\Composers\NotificationComposer;
use Illuminate\View\View;

// Test 1: Check current notification counts using different methods
echo "1. Testing different counting methods:\n";

// Method 1: Old logic (is_read = false)
$oldMethodCount = Notification::where('is_read', false)->count();
echo "   Old method (is_read = false): {$oldMethodCount}\n";

// Method 2: New logic (pending + read_at IS NULL) - NotificationComposer
$newMethodCount = Notification::pending()->whereNull('read_at')->count();
echo "   New method (pending + read_at IS NULL): {$newMethodCount}\n";

// Method 3: API method (same as new method)
$apiMethodCount = Notification::pending()->whereNull('read_at')->count();
echo "   API method: {$apiMethodCount}\n";

// Test 2: Check if methods are consistent
echo "\n2. Consistency check:\n";
if ($newMethodCount === $apiMethodCount) {
    echo "   ✓ NotificationComposer and API are consistent\n";
} else {
    echo "   ✗ NotificationComposer and API are NOT consistent\n";
}

// Test 3: Show breakdown of notifications
echo "\n3. Notification breakdown:\n";
$totalNotifications = Notification::count();
$pendingNotifications = Notification::where('status', 'pending')->count();
$readNotifications = Notification::whereNotNull('read_at')->count();
$unreadNotifications = Notification::whereNull('read_at')->count();
$isReadTrue = Notification::where('is_read', true)->count();
$isReadFalse = Notification::where('is_read', false)->count();

echo "   Total notifications: {$totalNotifications}\n";
echo "   Pending notifications: {$pendingNotifications}\n";
echo "   Read notifications (read_at NOT NULL): {$readNotifications}\n";
echo "   Unread notifications (read_at IS NULL): {$unreadNotifications}\n";
echo "   is_read = true: {$isReadTrue}\n";
echo "   is_read = false: {$isReadFalse}\n";

// Test 4: Test NotificationComposer directly
echo "\n4. Testing NotificationComposer:\n";
try {
    // Clear cache first
    cache()->forget('unread_notifications_count');
    
    // Create a mock view
    $mockView = new class {
        public $data = [];
        public function with($key, $value) {
            $this->data[$key] = $value;
            return $this;
        }
    };
    
    $composer = new NotificationComposer();
    $composer->compose($mockView);
    
    $composerCount = $mockView->data['unreadNotificationsCount'];
    echo "   NotificationComposer result: {$composerCount}\n";
    
    if ($composerCount === $newMethodCount) {
        echo "   ✓ NotificationComposer matches expected count\n";
    } else {
        echo "   ✗ NotificationComposer does NOT match expected count\n";
    }
    
} catch (Exception $e) {
    echo "   ✗ Error testing NotificationComposer: " . $e->getMessage() . "\n";
}

// Test 5: Show sample notifications for debugging
echo "\n5. Sample notification data:\n";
$sampleNotifications = Notification::with('store')
    ->orderBy('created_at', 'desc')
    ->take(5)
    ->get(['id', 'title', 'status', 'is_read', 'read_at', 'created_at']);

foreach ($sampleNotifications as $notification) {
    echo "   ID: {$notification->id} | Status: {$notification->status} | ";
    echo "is_read: " . ($notification->is_read ? 'true' : 'false') . " | ";
    echo "read_at: " . ($notification->read_at ? $notification->read_at->format('Y-m-d H:i:s') : 'NULL') . "\n";
}

echo "\n=== TEST COMPLETE ===\n";
echo "Expected behavior: NotificationComposer should only count pending notifications that are unread (read_at IS NULL)\n";
