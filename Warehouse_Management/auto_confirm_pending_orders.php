<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\PurchaseOrder;
use App\Models\Inventory;
use Illuminate\Support\Facades\DB;

echo "Auto-confirming existing pending purchase orders...\n";
echo "==================================================\n";

try {
    $pendingOrders = PurchaseOrder::where('status', 'pending')->with('items')->get();
    
    if ($pendingOrders->count() == 0) {
        echo "No pending orders found. All orders are already confirmed.\n";
        exit(0);
    }
    
    echo "Found {$pendingOrders->count()} pending orders to process:\n\n";
    
    DB::transaction(function() use ($pendingOrders) {
        foreach ($pendingOrders as $order) {
            echo "Processing Order ID: {$order->id} | Invoice: {$order->invoice_number}\n";
            
            // Update order status to confirmed
            $order->update(['status' => 'confirmed']);
            echo "  ✓ Status updated to 'confirmed'\n";
            
            // Update inventory for each item
            foreach ($order->items as $item) {
                $inventory = Inventory::firstOrCreate(
                    [
                        'warehouse_id' => $order->warehouse_id,
                        'product_id' => $item->product_id,
                    ],
                    ['quantity' => 0]
                );
                
                $oldQuantity = $inventory->quantity;
                $inventory->increment('quantity', $item->quantity);
                $newQuantity = $inventory->quantity;
                
                echo "  ✓ Inventory updated for Product ID {$item->product_id}: {$oldQuantity} → {$newQuantity} (+{$item->quantity})\n";
            }
            echo "\n";
        }
    });
    
    echo "✅ Successfully auto-confirmed {$pendingOrders->count()} pending orders!\n";
    echo "All purchase orders are now in 'confirmed' status with inventory updated.\n\n";
    
    // Verify the changes
    echo "Verification:\n";
    echo "============\n";
    $statuses = PurchaseOrder::selectRaw('status, COUNT(*) as count')->groupBy('status')->get();
    foreach($statuses as $status) {
        echo $status->status . ': ' . $status->count . " orders\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
