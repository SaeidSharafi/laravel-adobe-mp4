<?php

namespace App\Http\Controllers\Users\Notification;

use App\Http\Controllers\Controller;

class NotificationMarkAllAsReadController extends Controller
{
    public function __invoke()
    {
        auth()->user()?->unreadNotifications()->update(['read_at' => now()]);
        return redirect()->back();
    }
}
