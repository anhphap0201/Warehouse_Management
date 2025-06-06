<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\PurchaseOrder;

echo "Current Purchase Order Statuses:\n";
echo "================================\n";

try {
    $statuses = PurchaseOrder::selectRaw('status, COUNT(*) as count')->groupBy('status')->get();
    foreach($statuses as $status) {
        echo $status->status . ': ' . $status->count . " orders\n";
    }
    
    echo "\nRecent 10 Purchase Orders:\n";
    echo "=========================\n";
    $recent = PurchaseOrder::latest()->take(10)->get(['id', 'invoice_number', 'status', 'created_at']);
    foreach($recent as $order) {
        echo "ID: {$order->id} | Invoice: {$order->invoice_number} | Status: {$order->status} | Created: {$order->created_at}\n";
    }
    
    // Check if there are any pending orders
    $pendingCount = PurchaseOrder::where('status', 'pending')->count();
    echo "\nPending orders count: {$pendingCount}\n";
    
    if ($pendingCount > 0) {
        echo "\nExisting pending orders found! These need to be updated.\n";
        echo "Would you like to auto-confirm all existing pending orders? (This will also update inventory)\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
