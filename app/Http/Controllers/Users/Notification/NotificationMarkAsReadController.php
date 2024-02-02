<?php

namespace App\Http\Controllers\Users\Notification;

use App\Http\Controllers\Controller;
use Illuminate\Notifications\DatabaseNotification;

class NotificationMarkAsReadController extends Controller
{
    public function __invoke(DatabaseNotification $notification)
    {
        if ($notification->notifiable_id === auth()->user()?->id) {
            $notification->markAsRead();

        }
        return redirect()->back();
    }
}
