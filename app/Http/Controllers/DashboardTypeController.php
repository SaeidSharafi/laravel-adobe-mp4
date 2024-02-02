<?php

namespace App\Http\Controllers;

use App\Services\CacheService;
use Illuminate\Support\Facades\Session;

class DashboardTypeController extends Controller
{
    public function __invoke()
    {
        $type = request()->get('type', null);
        Session::forget('dashboard_type');
        $duration = config('auth.guards.web.remember', 5760);

        switch ($type) {
            case "manager":
                CacheService::setCookie('dashboard_type', 'manager', $duration);
                return redirect()->route('dashboard');
        }
        return redirect()->route('profile.view');
    }
}
