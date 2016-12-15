<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('App\Contracts\SocialLoginInterface', function ($app) {
            return new \App\Services\SocialLoginService();
        });

        $this->app->singleton('App\Contracts\LocationFinderInterface', function ($app) {
            return new \App\Services\IpInfoLocationFinder();
        });
    }
}
