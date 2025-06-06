<?php

require_once 'vendor/autoload.php';

// Initialize Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== TESTING SHIPMENT NOTIFICATIONS ===\n\n";

// Check latest shipment notifications
echo "Latest shipment notifications:\n";
$notifications = \App\Models\Notification::where('data->generation_type', 'like', '%shipment%')
    ->latest()
    ->take(5)
    ->get(['id', 'type', 'store_id', 'title', 'data', 'created_at']);

if ($notifications->count() > 0) {
    foreach($notifications as $n) {
        echo "ID: {$n->id}, Type: {$n->type}, Store: {$n->store_id}, Title: {$n->title}\n";
        echo "Created: {$n->created_at}\n";        $data = $n->data;
        echo "Generation Type: " . ($data['generation_type'] ?? 'N/A') . "\n";
        echo "Auto Generated: " . (($data['auto_generated'] ?? false) ? 'Yes' : 'No') . "\n";
        echo "---\n";
    }
} else {
    echo "No shipment notifications found!\n";
}

// Check total counts
echo "\nTOTAL COUNTS:\n";
echo "All notifications: " . \App\Models\Notification::count() . "\n";
echo "Auto-generated notifications: " . \App\Models\Notification::where('data->auto_generated', true)->count() . "\n";
echo "Return requests: " . \App\Models\Notification::where('data->generation_type', 'like', '%return%')->count() . "\n";
echo "Shipment requests: " . \App\Models\Notification::where('data->generation_type', 'like', '%shipment%')->count() . "\n";

// Check recent activity
echo "\nRECENT ACTIVITY (Last 24 hours):\n";
$recent = \App\Models\Notification::where('created_at', '>=', now()->subDay())
    ->get(['id', 'type', 'store_id', 'title', 'created_at']);

foreach($recent as $n) {
    echo "ID: {$n->id}, Type: {$n->type}, Store: {$n->store_id}, Title: {$n->title}, Created: {$n->created_at}\n";
}

echo "\n=== TEST COMPLETE ===\n";
