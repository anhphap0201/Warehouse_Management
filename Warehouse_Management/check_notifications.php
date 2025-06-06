<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Notification;

echo "Total notifications: " . Notification::count() . "\n";

$latest = Notification::latest()->first();
if ($latest) {
    echo "Latest notification:\n";
    echo "- Title: " . $latest->title . "\n";
    echo "- Type: " . $latest->type . "\n";
    echo "- Status: " . $latest->status . "\n";
    echo "- Created: " . $latest->created_at . "\n";
    echo "- Data: " . json_encode($latest->data, JSON_PRETTY_PRINT) . "\n";
} else {
    echo "No notifications found.\n";
}
