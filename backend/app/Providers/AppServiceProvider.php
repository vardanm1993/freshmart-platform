<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
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
        Gate::define('i18n.edit', function ($user) {
            return in_array($user->role, ['translator', 'reviewer'], true);
        });

        Gate::define('i18n.approve', function ($user) {
            return $user->role === 'reviewer';
        });
    }
}
