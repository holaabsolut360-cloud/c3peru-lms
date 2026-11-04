<?php

namespace App\Providers;

use App\Models\ConfiguracionApariencia;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::define('aprobar-pagos', fn (User $user) => $user->esAdmin());

        View::composer('*', function ($view): void {
            $view->with('marca', ConfiguracionApariencia::actual());
        });

        RateLimiter::for('verificacion-certificado', function (Request $request) {
            return Limit::perMinutes(15, 8)->by($request->ip() ?: 'local');
        });
    }
}
