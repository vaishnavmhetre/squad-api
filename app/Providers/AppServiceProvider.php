<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        /* Register Providers */
        $providers = [
            \Flipbox\LumenGenerator\LumenGeneratorServiceProvider::class
        ];

        foreach ($providers as $provider)
            app()->registerDeferredProvider($provider);

    }

    public function boot(){

        /* Load custom configs */
        /*$configs = [
            'database'
        ];

        foreach ($configs as $config)
            app()->configure($config);*/

    }
}
