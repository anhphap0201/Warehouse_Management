<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== CLEARING DATABASE ===\n";

try {
    // Disable foreign key checks
    DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    echo "✓ Foreign key checks disabled\n";

    // List of tables to clear in specific order (respecting foreign key constraints)
    $tablesToClear = [
        'purchase_order_items',
        'purchase_orders', 
        'store_inventories',
        'inventory',
        'notifications',
        'products',
        'categories',
        'stores',
        'warehouses',
        'suppliers',
        'cache',
        'cache_locks',
        // Don't clear users table to keep admin access
        // 'users',
        // 'password_reset_tokens',
        // 'sessions',
    ];

    $clearedTables = [];
    $skippedTables = [];

    foreach ($tablesToClear as $table) {
        if (Schema::hasTable($table)) {
            $recordCount = DB::table($table)->count();
            
            if ($recordCount > 0) {
                DB::table($table)->truncate();
                $clearedTables[] = "$table ($recordCount records)";
                echo "✓ Cleared table: $table ($recordCount records)\n";
            } else {
                $skippedTables[] = "$table (already empty)";
                echo "- Skipped table: $table (already empty)\n";
            }
        } else {
            echo "! Table not found: $table\n";
        }
    }

    // Re-enable foreign key checks
    DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    echo "✓ Foreign key checks re-enabled\n";

    echo "\n=== SUMMARY ===\n";
    echo "Cleared " . count($clearedTables) . " tables:\n";
    foreach ($clearedTables as $table) {
        echo "  - $table\n";
    }

    if (!empty($skippedTables)) {
        echo "\nSkipped " . count($skippedTables) . " empty tables:\n";
        foreach ($skippedTables as $table) {
            echo "  - $table\n";
        }
    }

    echo "\n✅ DATABASE CLEARED SUCCESSFULLY!\n";
    echo "Note: Users table was preserved to maintain admin access.\n";

} catch (Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    
    // Re-enable foreign key checks in case of error
    try {
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    } catch (Exception $ex) {
        // Ignore
    }
    
    exit(1);
}
