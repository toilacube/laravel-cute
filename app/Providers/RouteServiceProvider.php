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
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::prefix('api')
                ->group(base_path('routes/api.php'))
                ->group(base_path('routes/api/Products/Products.api.php'))
                ->group(base_path('routes/api/Products/ProductItem.api.php'))
                ->group(base_path('routes/api/Cart/Cart.api.php'))
                ->group(base_path('routes/api/Auth/Auth.api.php'))
                ->group(base_path('routes/api/User/User.api.php'))
                ->group(base_path('routes/api/Category/Category.api.php'))
                ->group(base_path('routes/api/Order/Order.api.php'))
                ->group(base_path('routes/api/Review/Review.api.php'))
                ->group(base_path('routes/api/Statistic/Statistic.api.php'));


            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
