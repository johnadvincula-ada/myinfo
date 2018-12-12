<?php

namespace CarroPublic\MyInfo;

use Illuminate\Support\ServiceProvider;

class MyInfoServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'carropublic');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'carropublic');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/myinfo.php', 'myinfo');

        // Register the service the package provides.
        $this->app->singleton('myinfo', function ($app) {
            return new MyInfo;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['myinfo'];
    }
    
    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/myinfo.php' => config_path('myinfo.php'),
        ], 'myinfo.config');

        $this->publishes([
            __DIR__.'/../ssl/private.pem' => storage_path('ssl'),
            __DIR__.'/../ssl/public.pem'  => storage_path('ssl'),
        ]);

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/carropublic'),
        ], 'myinfo.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/carropublic'),
        ], 'myinfo.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/carropublic'),
        ], 'myinfo.views');*/

        // Registering package commands.
        // $this->commands([]);
    }
}
