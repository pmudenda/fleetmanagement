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
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        Route::pattern('id', '[a-z]+');

        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::middleware('web')
                ->group(base_path('routes/vehicles.php'));

            Route::middleware('web')
                ->group(base_path('routes/users.php'));

            Route::middleware('web')
                ->group(base_path('routes/mechanics.php'));

            Route::middleware('web')
                ->group(base_path('routes/accidents.php'));

            Route::middleware('web')
                ->group(base_path('routes/admin.php'));

            Route::middleware('web')
                ->group(base_path('routes/data.php'));

            /*Route::middleware('web')
                 ->group(base_path('routes/fuel.php'));*/

            Route::middleware('web')
                ->group(base_path('routes/configuration.php'));

            Route::middleware('web')
                ->group(base_path('routes/report.php'));

            Route::middleware('web')
                ->group(base_path('routes/security.php'));

            Route::middleware('web')
                ->group(base_path('routes/tollcards.php'));

            Route::middleware('web')
                ->group(base_path('routes/drivers.php'));

            Route::middleware('web')
                ->group(base_path('routes/workshop.php'));

            Route::middleware('web')
                ->group(base_path('routes/reminders.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
