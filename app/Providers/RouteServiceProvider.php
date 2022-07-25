<?php

namespace App\Providers;

use App\Models\LimitLoginAttempt;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/dashboard';
    public $second;
    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            $limitLoginAttempt = LimitLoginAttempt::first();
            $maxAttempts    =  (int)$limitLoginAttempt->attempt;
            $decay          =  (int)$limitLoginAttempt->second;
           $key = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
                $seconds = RateLimiter::availableIn($key);
                Session::flash('msg', $seconds);
            }else{
              RateLimiter::hit($key, $decay);
            }
            $retries = RateLimiter::retriesLeft($key, $maxAttempts);
            Session::flash('retries', $retries);

        });
    }
}
