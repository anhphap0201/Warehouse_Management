<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Http\Controllers\Admin\AutoGenerationController;
use Illuminate\Http\Request;

echo "Testing Admin Auto-Generation Interface...\n";

// Test controller instantiation
try {
    $controller = new AutoGenerationController();
    echo "✅ Controller instantiated successfully\n";
      // Simulate request to get statistics
    $request = new Request();
    $response = $controller->index($request);
    
    if ($response instanceof \Illuminate\View\View) {
        echo "✅ Admin interface returns view successfully\n";
    } else {
        echo "❌ Admin interface returned unexpected response type\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error testing admin interface: " . $e->getMessage() . "\n";
}

echo "\nTesting commands availability:\n";

// Test if commands are registered by trying to run them with help
try {
    \Illuminate\Support\Facades\Artisan::call('stores:generate-return-requests', ['--help' => true]);
    echo "stores:generate-return-requests: ✅ Available\n";
} catch (Exception $e) {
    echo "stores:generate-return-requests: ❌ Not found\n";
}

try {
    \Illuminate\Support\Facades\Artisan::call('stores:smart-return-requests', ['--help' => true]);
    echo "stores:smart-return-requests: ✅ Available\n";
} catch (Exception $e) {
    echo "stores:smart-return-requests: ❌ Not found\n";
}

echo "\nTesting system is ready!\n";
