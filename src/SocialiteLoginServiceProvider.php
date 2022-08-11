<?php

namespace Bqroster\SocialiteLogin;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use function Termwind\ValueObjects\uppercase;

/**
 * Class SocialiteLoginServiceProvider
 * @package Bqroster\SocialiteLogin
 */
class SocialiteLoginServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/', 'bqr_socialite_migrations');

        $this->publishes([
            __DIR__ . '/../config/socialite-login.php' => base_path('config/socialite-login.php')
        ], 'socialite-login-config');

        $this->processSocialRoutes();
    }

    public function register()
    {
        $this->app->bind('socialite-login', function() {
            return new SocialiteLogin();
        });

        $this->mergeConfigFrom(__DIR__ . '/../config/socialite-login.php', 'socialite-login');
    }

    private function processSocialRoutes()
    {
        $socialRoutes = socialite_networks();

        throw_if(
            !is_array($socialRoutes) || empty($socialRoutes),
            new \Exception("config socialite-login.networks key must be an array and can not be empty")
        );

        Route::group(['namespace' => 'Bqroster\\SocialiteLogin\\Http\\Controllers', 'middleware' => 'web'], function () use ($socialRoutes) {
            foreach($socialRoutes as $key => $route) {
                if (is_array($route)) {
                    $network = $key;
                    $startClassName = \Str::studly($key);
                    $routeLogin = $route['login'] ?? null;
                    $routeRegister = $route['register'] ?? null;
                    $routeCallback = $route['callback'] ?? null;
                    $routeCancelled = $route['cancelled'] ?? null;
                    $routeRemoved = $route['removed'] ?? null;
                } else {
                    $network = $route;
                    $startClassName = \Str::studly($route);

                    $routeLogin = "{$route}/login";
                    $routeRegister = "{$route}/register";
                    $routeCallback = "{$route}/login/callback";
                    $routeCancelled = "{$route}/login/cancelled";
                    $routeRemoved = "{$route}/login/removed";
                }

                $socialController = "${startClassName}LoginController";
                throw_if(
                    !class_exists("Bqroster\SocialiteLogin\Http\Controllers\\${socialController}"),
                    new \Exception("${startClassName} login/register is not supported on this package.")
                );

                if (!is_null($routeLogin)) {
                    Route::get($routeLogin, "{$socialController}@login");
                }

                if (!is_null($routeRegister)) {
                    Route::get($routeRegister, "{$socialController}@register");
                }

                if (!is_null($routeCallback)) {
                    Route::get($routeCallback, "{$socialController}@callback");
                }

                if (!is_null($routeCancelled)) {
                    Route::get($routeCancelled, "{$socialController}@cancelled");
                }

                if (!is_null($routeRemoved)) {
                    Route::get($routeRemoved, "{$socialController}@removed");
                }

                $routeCallbackUrl = env('APP_URL') . '/' . $routeCallback;
                $this->setConfigServices($network, $routeCallbackUrl);
            }
        });
    }

    /**
     * set socialite networks services keys
     *
     * @param string $network
     * @param string $routeCallback
     */
    private function setConfigServices($network, $routeCallback)
    {
        $upNetwork = strtoupper($network);

        config(["services.{$network}.client_id" => env("{$upNetwork}_CLIENT_ID")]);
        config(["services.{$network}.client_secret" => env("{$upNetwork}_CLIENT_SECRET")]);
        config(["services.{$network}.redirect" => $routeCallback]);
    }
}