<?php

namespace PulkitJalan\IPGeoLocation;

use Illuminate\Support\ServiceProvider;
use PulkitJalan\IPGeoLocation\Console\UpdateCommand;

class GeoIPServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     */
    public function boot()
    {
        $this->app['geoip'] = function ($app) {
            return $app['PulkitJalan\IPGeoLocation\IPGeoLocation'];
        };

        if ($this->app->runningInConsole()) {
            $this->commands(['PulkitJalan\IPGeoLocation\Console\UpdateCommand']);
        }

        if (function_exists('config_path')) {
            $this->publishes([
                __DIR__.'/../config/geoip.php' => config_path('geoip.php'),
            ], 'config');
        }
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/geoip.php', 'geoip');

        $this->registerGeoIP();

        $this->registerUpdateCommand();
    }

    /**
     * Register the main geoip wrapper.
     */
    protected function registerGeoIP()
    {
        $this->app->singleton('PulkitJalan\IPGeoLocation\IPGeoLocation', function ($app) {
            return new IPGeoLocation($app['config']['geoip']);
        });
    }

    /**
     * Register the geoip update console command.
     */
    protected function registerUpdateCommand()
    {
        $this->app->singleton('PulkitJalan\IPGeoLocation\Console\UpdateCommand', function ($app) {
            return new UpdateCommand($app['config']['geoip']);
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
            'PulkitJalan\IPGeoLocation\IPGeoLocation',
            'PulkitJalan\IPGeoLocation\Console\UpdateCommand',
            'geoip',
        ];
    }
}
