<?php

namespace App\Services;

use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class DashboardService
{
    public static function redirectToDashboard($user, $redirectToDashboard = false): null|RedirectResponse
    {
        $duration = config('auth.guards.web.remember', 5760);

        if ($redirectToDashboard) {
            if (Auth::user()?->is_admin || Auth::user()?->can('dashboard.admin.view')) {
                CacheService::setCookie('dashboard_type', 'manager', $duration);
                return redirect()->intended(RouteServiceProvider::HOME);
            }
        }

        if ( ! $user->is_admin && ! $user->can('dashboard.admin.view')) {

            return redirect()->route('profile.view');
        }

        return null;

    }

}
