<?php
/**
 * Script to check the notifications table schema
 */

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Checking Notifications Table Schema ===\n\n";

try {
    // Check if table exists
    if (Schema::hasTable('notifications')) {
        echo "✓ Notifications table exists\n\n";
        
        // Get all columns
        $columns = Schema::getColumnListing('notifications');
        echo "Columns in notifications table:\n";
        foreach ($columns as $column) {
            echo "- {$column}\n";
        }
        
        echo "\n=== Column Details ===\n";
        
        // Get detailed column information
        $tableName = 'notifications';
        $query = "DESCRIBE {$tableName}";
        $columnDetails = DB::select($query);
        
        foreach ($columnDetails as $column) {
            $field = $column->Field;
            $type = $column->Type;
            $null = $column->Null;
            $key = $column->Key;
            $default = $column->Default;
            $extra = $column->Extra;
            
            echo "{$field}: {$type} | Null: {$null} | Key: {$key} | Default: " . ($default ?? 'NULL') . " | Extra: {$extra}\n";
        }
        
    } else {
        echo "✗ Notifications table does not exist\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
