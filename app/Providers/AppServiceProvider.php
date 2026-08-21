<?php

namespace App\Providers;

use App\Models\CarteLibre;
use App\Policies\CarteLibrePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

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
        Gate::policy(CarteLibre::class, CarteLibrePolicy::class);

        Gate::before(function ($user) {
            return method_exists($user, 'hasRole') && $user->hasRole('Super Admin') ? true : null;
        });

        if ($this->app->environment('local')) {
            URL::forceScheme('https');
        }
    }
}
