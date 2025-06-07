<?php

namespace App\Http\View\Composers;

use App\Models\Notification;
use Illuminate\View\View;

class NotificationComposer
{
    /**
     * Bind data to the view.
     */    public function compose(View $view): void
    {
        $unreadNotificationsCount = cache()->remember(
            'unread_notifications_count', 
            now()->addMinutes(5),
            function () {
                return Notification::pending()->whereNull('read_at')->count();
            }
        );

        $view->with('unreadNotificationsCount', $unreadNotificationsCount);
    }
}
