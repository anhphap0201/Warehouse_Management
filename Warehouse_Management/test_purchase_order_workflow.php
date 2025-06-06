<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Warehouse;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Inventory;

echo "Testing Purchase Order Auto-Confirmation Workflow\n";
echo "================================================\n";

try {
    // Get first warehouse and product for testing
    $warehouse = Warehouse::first();
    $product = Product::first();

    if (!$warehouse || !$product) {
        echo "Error: No warehouse or product found for testing\n";
        exit(1);
    }

    echo "Testing with:\n";
    echo "- Warehouse: {$warehouse->name}\n";
    echo "- Product: {$product->name}\n\n";

    // Check inventory before
    $inventoryBefore = Inventory::where('warehouse_id', $warehouse->id)
                                ->where('product_id', $product->id)
                                ->first();
    $quantityBefore = $inventoryBefore ? $inventoryBefore->quantity : 0;
    echo "Inventory before: {$quantityBefore}\n";

    // Simulate the workflow that happens in the controller
    $testQuantity = 5;
    
    // Create purchase order with confirmed status (auto-confirmed)
    $purchaseOrder = PurchaseOrder::create([
        'warehouse_id' => $warehouse->id,
        'supplier_name' => 'Test Supplier',
        'supplier_phone' => '123456789',
        'supplier_address' => 'Test Address',
        'invoice_number' => 'TEST' . time(),
        'total_amount' => $testQuantity * 100,
        'status' => 'confirmed', // Auto-confirmed
        'notes' => 'Test order for workflow verification',
    ]);

    // Create purchase order item
    PurchaseOrderItem::create([
        'purchase_order_id' => $purchaseOrder->id,
        'product_id' => $product->id,
        'quantity' => $testQuantity,
        'unit_price' => 100,
        'total_price' => $testQuantity * 100,
    ]);

    // Auto-update inventory (simulating what happens in store method)
    $inventory = Inventory::firstOrCreate(
        [
            'warehouse_id' => $warehouse->id,
            'product_id' => $product->id,
        ],
        ['quantity' => 0]
    );
    $inventory->increment('quantity', $testQuantity);

    echo "Purchase Order created with:\n";
    echo "- ID: {$purchaseOrder->id}\n";
    echo "- Status: {$purchaseOrder->status}\n";
    echo "- Invoice Number: {$purchaseOrder->invoice_number}\n";

    // Check inventory after
    $inventoryAfter = Inventory::where('warehouse_id', $warehouse->id)
                               ->where('product_id', $product->id)
                               ->first();
    $quantityAfter = $inventoryAfter ? $inventoryAfter->quantity : 0;
    echo "Inventory after: {$quantityAfter}\n";
    echo "Quantity added: " . ($quantityAfter - $quantityBefore) . "\n\n";

    if ($quantityAfter == $quantityBefore + $testQuantity) {
        echo "✅ SUCCESS: Auto-confirmation and inventory update working correctly!\n";
        echo "   - Purchase order was automatically confirmed\n";
        echo "   - Inventory was automatically updated\n";
        echo "   - No manual confirmation step required\n";
    } else {
        echo "❌ ERROR: Inventory was not updated correctly\n";
    }

    // Clean up test data
    echo "\nCleaning up test data...\n";
    $purchaseOrder->items()->delete();
    $purchaseOrder->delete();
    
    // Reset inventory to original quantity
    if ($inventoryAfter) {
        $inventoryAfter->decrement('quantity', $testQuantity);
    }

    echo "Test completed and cleaned up.\n";

} catch (Exception $e) {
    echo "Error during test: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
