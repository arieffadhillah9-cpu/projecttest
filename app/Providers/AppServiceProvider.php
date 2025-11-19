<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    // [EDIT DISINI]: Mengubah pengalihan default dari /home ke /admin/dashboard
    public const HOME = '/admin/dashboardmin'; 

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        // Mendefinisikan batas waktu akses API (penting untuk keamanan)
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            // Memuat rute-rute API (routes/api.php)
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            // Memuat rute-rute Web (routes/web.php)
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}