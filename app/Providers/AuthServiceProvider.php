<?php

namespace App\Providers;

use App\Models\Auth\User;
use App\Policies\Auth\RolePolicy;
use App\Policies\Auth\UserPolicy;
use App\Policies\Reports\ActivityLogPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Role;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies
        = [
            User::class                      => UserPolicy::class,
            Role::class                      => RolePolicy::class,
            Activity::class                  => ActivityLogPolicy::class,
        ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Implicitly grant "Super Admin" role all permissions
        // This works in the app by using gate-related functions like auth()->user->can() and @can()
        Gate::before(fn ($user, $ability) => $user->is_admin ? true : null);
    }
}
