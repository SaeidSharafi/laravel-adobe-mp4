<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     */
    public function share(Request $request): array
    {
        if ($request->user() && ! Session::has('dashboard_type')) {
            if ($dashboard_type = Cookie::get('dashboard_type')) {
                Session::put('dashboard_type', $dashboard_type);
            }
        }

        return array_merge(parent::share($request), [
            'auth' => [
                'user'          => $request->user(),
                'permissions'   => $request->user()?->getAllPermissions()->pluck('name')->toArray(),
                'roles'         => $request->user()?->getRoleNames(),
                'overrideroles' => $request->user()?->getAllowedOverrides(),
            ],
            'flash' => function () use ($request) {
                return [
                    'status' => $request->session()->get('status'),
                ];
            },
            'dashboard' => $request->user() ? Session::get('dashboard_type', 'user') : null,
            'returnUrl' => Session::get('return_url'),
            'filters'   => Session::get('filters'),
            'ziggy'     => function () use ($request) {
                return array_merge((new Ziggy())->toArray(), [
                    'location' => $request->url(),
                ]);
            },
            'locale'  => app()->getLocale(),
            'version' => config('app.version'),
        ]);
    }
}
