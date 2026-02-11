<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use App\Models\Turno;
use App\Policies\TurnoPolicy;

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
        Gate::policy(Turno::class, TurnoPolicy::class);

        // Rate limiter para solicitud de turnos
        RateLimiter::for('turnos', function (Request $request) {
            return Limit::perMinute(50)->by($request->ip());
        });

        // Rate limiter para API pública
        RateLimiter::for('api-publica', function (Request $request) {
            return Limit::perMinute(60)->by($request->ip());
        });
    }
}
