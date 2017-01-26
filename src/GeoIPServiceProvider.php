<?php

namespace PulkitJalan\GeoIP;

use Illuminate\Support\ServiceProvider;
use PulkitJalan\GeoIP\Console\UpdateCommand;

class GeoIPServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the service provider.
     */
    public function boot()
    {
        $this->app['geoip'] = function ($app) {
            return $app['PulkitJalan\GeoIP\GeoIP'];
        };

        if ($this->app->runningInConsole()) {
            $this->commands(['PulkitJalan\GeoIP\Console\UpdateCommand']);
        }

        if (function_exists('config_path')) {
            $this->publishes([
                __DIR__.'/config/config.php' => config_path('geoip.php'),
            ], 'config');
        }
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/config/config.php', 'geoip');

        $this->registerGeoIP();

        $this->registerUpdateCommand();
    }

    /**
     * Register the main geoip wrapper.
     */
    protected function registerGeoIP()
    {
        $this->app->singleton('PulkitJalan\GeoIP\GeoIP', function ($app) {
            return new GeoIP($app['config']['geoip']);
        });
    }

    /**
     * Register the geoip update console command.
     */
    protected function registerUpdateCommand()
    {
        $this->app->singleton('PulkitJalan\GeoIP\Console\UpdateCommand', function ($app) {
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
            'PulkitJalan\GeoIP\GeoIP',
            'PulkitJalan\GeoIP\Console\UpdateCommand',
            'geoip',
        ];
    }
}
