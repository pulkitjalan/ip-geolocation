<?php

namespace PulkitJalan\IPGeolocation;

use Illuminate\Support\ServiceProvider;
use PulkitJalan\IPGeolocation\Console\UpdateCommand;

class IPGeolocationServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([UpdateCommand::class]);
        }

        if (function_exists('config_path')) {
            $this->publishes([
                __DIR__.'/../config/ip-geolocation.php' => config_path('ip-geolocation.php'),
            ], 'config');
        }
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/ip-geolocation.php', 'ip-geolocation');

        $this->registerIPGeolocation();

        $this->registerUpdateCommand();
    }

    /**
     * Register the main ipGeolocation wrapper.
     */
    protected function registerIPGeolocation()
    {
        $this->app->singleton(IPGeolocation::class, function ($app) {
            return new IPGeolocation($app['config']['ip-geolocation']);
        });
    }

    /**
     * Register the ipGeolocation update console command.
     */
    protected function registerUpdateCommand()
    {
        $this->app->singleton(UpdateCommand::class, function ($app) {
            return new UpdateCommand($app['config']['ip-geolocation']);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return [
            IPGeolocation::class,
            UpdateCommand::class,
        ];
    }
}
