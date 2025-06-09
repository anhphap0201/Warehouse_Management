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
    {        // Lập lịch yêu cầu trả hàng
        // Tạo yêu cầu trả hàng ngẫu nhiên mỗi ngày lúc 9 giờ sáng
        $schedule->command('stores:generate-return-requests')
                 ->dailyAt('09:00')
                 ->withoutOverlapping()
                 ->runInBackground();

        // Tạo yêu cầu trả hàng ngẫu nhiên mỗi 4 giờ với tỷ lệ thấp hơn
        $schedule->command('stores:generate-return-requests', ['--percentage=10'])
                 ->cron('0 */4 * * *')
                 ->withoutOverlapping()
                 ->runInBackground();

        // Yêu cầu trả hàng thông minh dựa trên phân tích tồn kho - chạy hai lần mỗi ngày
        $schedule->command('stores:smart-return-requests')
                 ->twiceDaily(8, 16)
                 ->withoutOverlapping()
                 ->runInBackground();

        // Yêu cầu trả hàng thông minh trong giờ làm việc (mỗi 6 giờ)
        $schedule->command('stores:smart-return-requests')
                 ->cron('0 6,12,18 * * *')
                 ->withoutOverlapping()
                 ->runInBackground();        // Lập lịch yêu cầu gửi hàng
        // Tạo yêu cầu gửi hàng ngẫu nhiên mỗi ngày lúc 10 giờ sáng
        $schedule->command('stores:generate-shipment-requests')
                 ->dailyAt('10:00')
                 ->withoutOverlapping()
                 ->runInBackground();

        // Tạo yêu cầu gửi hàng ngẫu nhiên mỗi 6 giờ với tỷ lệ thấp hơn
        $schedule->command('stores:generate-shipment-requests', ['--percentage=15'])
                 ->cron('0 */6 * * *')
                 ->withoutOverlapping()
                 ->runInBackground();

        // Yêu cầu gửi hàng thông minh dựa trên phân tích tồn kho - chạy ba lần mỗi ngày
        $schedule->command('stores:smart-shipment-requests')
                 ->cron('0 7,13,19 * * *')
                 ->withoutOverlapping()
                 ->runInBackground();

        // Yêu cầu gửi hàng thông minh cho mức tồn kho tới hạn (mỗi 3 giờ trong giờ làm việc)
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
