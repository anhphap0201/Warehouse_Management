<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Http\View\Composers\NotificationComposer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Đăng ký View Composer cho thông báo điều hướng
        View::composer(['layouts.navigation', 'layouts.app'], NotificationComposer::class);
        
        // Chia sẻ biến thông báo toàn cục cho tất cả các view
        View::composer('*', function ($view) {
            try {
                $unreadNotificationsCount = Cache::remember(
                    'unread_notifications_count', 
                    now()->addMinutes(5),
                    function () {
                        // Check if notifications table exists
                        if (DB::getSchemaBuilder()->hasTable('notifications')) {
                            return \App\Models\Notification::pending()->whereNull('read_at')->count();
                        }
                        return 0;
                    }
                );
            } catch (\Exception $e) {
                // Fallback to 0 if there's any error
                $unreadNotificationsCount = 0;
            }
            
            $view->with('unreadNotificationsCount', $unreadNotificationsCount);
        });
    }
}
