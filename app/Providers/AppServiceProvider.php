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
        $this->app->singleton('App\Contracts\EventServiceInterface', function ($app) {
            return new \App\Services\EventService();
        });

        $this->app->singleton('App\Contracts\RegistrarServiceInterface', function ($app) {
            return new \App\Services\RegistrarService();
        });

        $this->app->singleton('App\Contracts\GeoIpServiceInterface', function ($app) {
            return new \App\Services\IpInfoService();
        });
    }
}
