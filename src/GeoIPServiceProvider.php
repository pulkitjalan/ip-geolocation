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
        $this->app['PulkitJalan\GeoIP\GeoIP'] = function ($app) {
            return $app['geoip'];
        };

        if (function_exists('config_path')) {
            $this->publishes([
                __DIR__.'/config/config.php' => config_path('geoip.php'),
            ], 'config');
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/config/config.php', 'geoip');

        $this->registerGeoIP();

        $this->registerUpdateCommand();
    }

    /**
     * Register the main geoip wrapper.
     *
     * @return void
     */
    protected function registerGeoIP()
    {
        $this->app['geoip'] = $this->app->share(function ($app) {
            return new GeoIP(config('geoip'));
        });
    }

    /**
     * Register the geoip update console command.
     *
     * @return void
     */
    protected function registerUpdateCommand()
    {
        $this->app['command.geoip.update'] = $this->app->share(function ($app) {
            return new UpdateCommand(config('geoip'));
        });

        $this->commands(['command.geoip.update']);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return ['geoip', 'command.geoip.update', 'PulkitJalan\GeoIP\GeoIP'];
    }
}
