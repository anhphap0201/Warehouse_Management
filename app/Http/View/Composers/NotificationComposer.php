<?php

namespace App\Http\View\Composers;

use App\Models\Notification;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class NotificationComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        try {
            $unreadNotificationsCount = Cache::remember(
                'unread_notifications_count', 
                now()->addMinutes(5),
                function () {
                    // Check if notifications table exists and has data
                    if (DB::getSchemaBuilder()->hasTable('notifications')) {
                        return Notification::pending()->whereNull('read_at')->count();
                    }
                    return 0;
                }
            );
        } catch (\Exception $e) {
            // Fallback to 0 if there's any error
            $unreadNotificationsCount = 0;
        }

        $view->with('unreadNotificationsCount', $unreadNotificationsCount);
    }
}
