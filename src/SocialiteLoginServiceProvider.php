<?php

namespace Bqroster\SocialiteLogin;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

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
        $this->mergeConfigFrom(
            __DIR__ . '/../config/socialite-login.php', 'socialite-login'
        );
    }

    private function processSocialRoutes()
    {
        $socialRoutes = config('socialite-login.networks');

        throw_if(
            !is_array($socialRoutes) || empty($socialRoutes),
            new \Exception("config socialite-login.networks key must be an array and can not be empty")
        );

        /**
         * @register
         * social routes
         */
        Route::group(['namespace' => 'Bqroster\\SocialiteLogin\\Http\\Controllers', 'middleware' => 'web'], function () use ($socialRoutes) {
            foreach($socialRoutes as $key => $route) {
                if (is_array($route)) {
                    $network = $key;
                    $startClassName = \Str::studly($key);
                    $routeLogin = $route['login'] ?? null;
                    $routeRegister = $route['register'] ?? null;
                    $routeCallback = $route['callback'] ?? null;
                } else {
                    $network = $route;
                    $startClassName = \Str::studly($route);

                    $routeLogin = "{$route}/login";
                    $routeRegister = "{$route}/register";
                    $routeCallback = "{$route}/login/callback";
                }

                $socialController = "${startClassName}LoginController";
                throw_if(
                    !class_exists("Bqroster\SocialiteLogin\Http\Controllers\\${socialController}"),
                    new \Exception("${startClassName} login/register is not supported on this package.")
                );

                if (!is_null($routeLogin)) {
                    Route::get($routeLogin, "{$socialController}@login")->name("{$network}.login");
                }

                if (!is_null($routeRegister)) {
                    Route::get($routeRegister, "{$socialController}@register")->name("{$network}.register");
                }

                if (!is_null($routeCallback)) {
                    Route::get($routeCallback, "{$socialController}@callback")->name("{$network}.callback");
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