<?php

namespace App\Providers;

use App\Models\Auth\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        JsonResource::withoutWrapping();

        Model::preventLazyLoading( ! app()->isProduction());

        include_once __DIR__ . '/../Helpers/helpers.php';

        Carbon::macro('verta', function () {
            if ('fa' === app()->getLocale()) {
                return verta(self::this()->format('Y-m-d'));
            }
            return self::this();
        });

        if (App::environment('local')) {
            if ( ! $email = config('app.auto_login_email')) {
                return;
            }
            if (( ! App::runningInConsole() || App::runningUnitTests()) && ! auth()->check()) {
                $user = User::where('email', '=', $email)->first();

                if ($user) {
                    auth()->login($user);
                }
            }
        }
    }
}
