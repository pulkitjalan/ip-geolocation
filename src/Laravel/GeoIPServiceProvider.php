<?php

namespace PulkitJalan\GeoIP\Laravel;

use Illuminate\Support\ServiceProvider;

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
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->config->package('pulkitjalan/geoip', realpath(__DIR__.'/config'), 'geoip');

        $this->app['geoip'] = $this->app->share(function ($app) {
            return new GeoIP($app->config->get('geoip::database'));
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return ['geoip', 'PulkitJalan\GeoIP\GeoIP'];
    }
}
