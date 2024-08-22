<?php

namespace PulkitJalan\IPGeoLocation;

use Illuminate\Support\ServiceProvider;
use PulkitJalan\IPGeoLocation\Console\UpdateCommand;

class IPGeolocationServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     */
    public function boot()
    {
        $this->app['ipGeolocation'] = function ($app) {
            return $app['PulkitJalan\IPGeoLocation\IPGeoLocation'];
        };

        if ($this->app->runningInConsole()) {
            $this->commands(['PulkitJalan\IPGeoLocation\Console\UpdateCommand']);
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
        $this->app->singleton('PulkitJalan\IPGeoLocation\IPGeoLocation', function ($app) {
            return new IPGeoLocation($app['config']['ip-geolocation']);
        });
    }

    /**
     * Register the ipGeolocation update console command.
     */
    protected function registerUpdateCommand()
    {
        $this->app->singleton('PulkitJalan\IPGeoLocation\Console\UpdateCommand', function ($app) {
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
            IPGeoLocation::class,
            UpdateCommand::class,
        ];
    }
}
