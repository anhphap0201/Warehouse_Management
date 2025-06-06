<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Return Requests Scheduling
        // Generate random return requests every day at 9 AM
        $schedule->command('stores:generate-return-requests')
                 ->dailyAt('09:00')
                 ->withoutOverlapping()
                 ->runInBackground();

        // Generate random return requests every 4 hours with lower percentage
        $schedule->command('stores:generate-return-requests', ['--percentage=10'])
                 ->cron('0 */4 * * *')
                 ->withoutOverlapping()
                 ->runInBackground();

        // Smart return requests based on inventory analysis - runs twice daily
        $schedule->command('stores:smart-return-requests')
                 ->twiceDaily(8, 16)
                 ->withoutOverlapping()
                 ->runInBackground();

        // Smart return requests during business hours (every 6 hours)
        $schedule->command('stores:smart-return-requests')
                 ->cron('0 6,12,18 * * *')
                 ->withoutOverlapping()
                 ->runInBackground();

        // Shipment Requests Scheduling
        // Generate random shipment requests every day at 10 AM
        $schedule->command('stores:generate-shipment-requests')
                 ->dailyAt('10:00')
                 ->withoutOverlapping()
                 ->runInBackground();

        // Generate random shipment requests every 6 hours with lower percentage
        $schedule->command('stores:generate-shipment-requests', ['--percentage=15'])
                 ->cron('0 */6 * * *')
                 ->withoutOverlapping()
                 ->runInBackground();

        // Smart shipment requests based on inventory analysis - runs three times daily
        $schedule->command('stores:smart-shipment-requests')
                 ->cron('0 7,13,19 * * *')
                 ->withoutOverlapping()
                 ->runInBackground();

        // Smart shipment requests for critical stock levels (every 3 hours during business)
        $schedule->command('stores:smart-shipment-requests', ['--low-stock-threshold=5'])
                 ->cron('0 8,11,14,17 * * *')
                 ->withoutOverlapping()
                 ->runInBackground();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
