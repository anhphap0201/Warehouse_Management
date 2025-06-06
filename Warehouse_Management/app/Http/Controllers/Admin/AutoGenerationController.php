<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class AutoGenerationController extends Controller
{
    /**
     * Show the auto-generation dashboard
     */
    public function index()
    {
        return view('admin.auto-generation.index');
    }

    /**
     * Generate random return requests manually
     */
    public function generateRandomRequests(Request $request)
    {
        $request->validate([
            'percentage' => 'nullable|integer|min:1|max:100',
            'min_products' => 'nullable|integer|min:1',
            'max_products' => 'nullable|integer|min:1',
            'store_ids' => 'nullable|array',
            'store_ids.*' => 'exists:stores,id'
        ]);

        try {
            $options = [];
            
            if ($request->percentage) {
                $options['--percentage'] = $request->percentage;
            }
            if ($request->min_products) {
                $options['--min-products'] = $request->min_products;
            }
            if ($request->max_products) {
                $options['--max-products'] = $request->max_products;
            }
            if ($request->store_ids) {
                $options['--stores'] = $request->store_ids;
            }

            // Capture command output
            $exitCode = Artisan::call('stores:generate-return-requests', $options);
            $output = Artisan::output();

            if ($exitCode === 0) {
                return redirect()->back()->with('success', 'Yêu cầu trả hàng ngẫu nhiên đã được tạo thành công!')
                    ->with('command_output', $output);
            } else {
                return redirect()->back()->with('error', 'Có lỗi xảy ra khi tạo yêu cầu.')
                    ->with('command_output', $output);
            }

        } catch (\Exception $e) {
            Log::error('Manual random generation failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Generate smart return requests manually
     */
    public function generateSmartRequests(Request $request)
    {
        $request->validate([
            'dry_run' => 'nullable|boolean',
            'min_overstock_days' => 'nullable|integer|min:1',
            'low_turnover_threshold' => 'nullable|numeric|min:0|max:1'
        ]);

        try {
            $options = [];
            
            if ($request->dry_run) {
                $options['--dry-run'] = true;
            }
            if ($request->min_overstock_days) {
                $options['--min-overstock-days'] = $request->min_overstock_days;
            }
            if ($request->low_turnover_threshold) {
                $options['--low-turnover-threshold'] = $request->low_turnover_threshold;
            }

            // Capture command output
            $exitCode = Artisan::call('stores:smart-return-requests', $options);
            $output = Artisan::output();

            if ($exitCode === 0) {
                $message = $request->dry_run ? 
                    'Phân tích hoàn thành! Xem kết quả dưới đây.' : 
                    'Yêu cầu trả hàng thông minh đã được tạo thành công!';

                return redirect()->back()->with('success', $message)
                    ->with('command_output', $output);
            } else {
                return redirect()->back()->with('error', 'Có lỗi xảy ra khi thực hiện phân tích.')
                    ->with('command_output', $output);
            }

        } catch (\Exception $e) {
            Log::error('Manual smart generation failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Generate random shipment requests manually
     */
    public function generateRandomShipmentRequests(Request $request)
    {
        $request->validate([
            'percentage' => 'nullable|integer|min:1|max:100',
            'min_products' => 'nullable|integer|min:1',
            'max_products' => 'nullable|integer|min:1',
            'min_quantity' => 'nullable|integer|min:1',
            'max_quantity' => 'nullable|integer|min:1',
            'store_ids' => 'nullable|array',
            'store_ids.*' => 'exists:stores,id'
        ]);

        try {
            $options = [];
            
            if ($request->percentage) {
                $options['--percentage'] = $request->percentage;
            }
            if ($request->min_products) {
                $options['--min-products'] = $request->min_products;
            }
            if ($request->max_products) {
                $options['--max-products'] = $request->max_products;
            }
            if ($request->min_quantity) {
                $options['--min-quantity'] = $request->min_quantity;
            }
            if ($request->max_quantity) {
                $options['--max-quantity'] = $request->max_quantity;
            }
            if ($request->store_ids) {
                $options['--stores'] = $request->store_ids;
            }

            // Capture command output
            $exitCode = Artisan::call('stores:generate-shipment-requests', $options);
            $output = Artisan::output();

            if ($exitCode === 0) {
                return redirect()->back()->with('success', 'Yêu cầu gửi hàng ngẫu nhiên đã được tạo thành công!')
                    ->with('command_output', $output);
            } else {
                return redirect()->back()->with('error', 'Có lỗi xảy ra khi tạo yêu cầu gửi hàng.')
                    ->with('command_output', $output);
            }

        } catch (\Exception $e) {
            Log::error('Manual random shipment generation failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Generate smart shipment requests manually
     */
    public function generateSmartShipmentRequests(Request $request)
    {
        $request->validate([
            'dry_run' => 'nullable|boolean',
            'min_shortage_days' => 'nullable|integer|min:1',
            'low_stock_threshold' => 'nullable|integer|min:1',
            'demand_multiplier' => 'nullable|numeric|min:0.1|max:10'
        ]);

        try {
            $options = [];
            
            if ($request->dry_run) {
                $options['--dry-run'] = true;
            }
            if ($request->min_shortage_days) {
                $options['--min-shortage-days'] = $request->min_shortage_days;
            }
            if ($request->low_stock_threshold) {
                $options['--low-stock-threshold'] = $request->low_stock_threshold;
            }
            if ($request->demand_multiplier) {
                $options['--demand-multiplier'] = $request->demand_multiplier;
            }

            // Capture command output
            $exitCode = Artisan::call('stores:smart-shipment-requests', $options);
            $output = Artisan::output();

            if ($exitCode === 0) {
                $message = $request->dry_run ? 
                    'Phân tích yêu cầu gửi hàng hoàn thành! Xem kết quả dưới đây.' :
                    'Yêu cầu gửi hàng thông minh đã được tạo thành công!';

                return redirect()->back()->with('success', $message)
                    ->with('command_output', $output);
            } else {
                return redirect()->back()->with('error', 'Có lỗi xảy ra khi thực hiện phân tích gửi hàng.')
                    ->with('command_output', $output);
            }

        } catch (\Exception $e) {
            Log::error('Manual smart shipment generation failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }    /**
     * Get generation statistics
     */
    public function getStats()
    {
        $stats = [
            'total_auto_generated' => \App\Models\Notification::where('data->auto_generated', true)->count(),
            'today_generated' => \App\Models\Notification::where('data->auto_generated', true)
                ->whereDate('created_at', today())->count(),
            'pending_auto' => \App\Models\Notification::where('data->auto_generated', true)
                ->where('status', 'pending')->count(),
            'last_generation' => \App\Models\Notification::where('data->auto_generated', true)
                ->latest()->first()?->created_at,
            
            // Return request statistics
            'return_requests' => [
                'total' => \App\Models\Notification::where('data->auto_generated', true)
                    ->where('data->generation_type', 'like', '%return%')->count(),
                'today' => \App\Models\Notification::where('data->auto_generated', true)
                    ->where('data->generation_type', 'like', '%return%')
                    ->whereDate('created_at', today())->count(),
                'pending' => \App\Models\Notification::where('data->auto_generated', true)
                    ->where('data->generation_type', 'like', '%return%')
                    ->where('status', 'pending')->count(),
            ],
            
            // Shipment request statistics
            'shipment_requests' => [
                'total' => \App\Models\Notification::where('data->auto_generated', true)
                    ->where('data->generation_type', 'like', '%shipment%')->count(),
                'today' => \App\Models\Notification::where('data->auto_generated', true)
                    ->where('data->generation_type', 'like', '%shipment%')
                    ->whereDate('created_at', today())->count(),
                'pending' => \App\Models\Notification::where('data->auto_generated', true)
                    ->where('data->generation_type', 'like', '%shipment%')
                    ->where('status', 'pending')->count(),
            ]
        ];

        return response()->json($stats);
    }
}
