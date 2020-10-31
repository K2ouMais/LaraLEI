<?php

namespace K2ouMais\LaraLEI;

use Illuminate\Support\ServiceProvider;

class LEIServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Access the config file without publishing it
        $this->mergeConfigFrom(__DIR__.'/../config/LaraLEI.php', 'LaraLEI');

        // Register LEI::class
        $this->app->singleton(LEI::class, function () {
            return new LEI();
        });
    }

    public function boot()
    {
        // If you want to publish the file
        $this->publishes([__DIR__.'/../config/LaraLEI.php' => config_path('LaraLEI.php')], 'config');
    }
}
