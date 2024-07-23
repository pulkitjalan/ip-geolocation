<?php

namespace PulkitJalan\IPGeoLocation\Facades;

use Illuminate\Support\Facades\Facade;

class IPGeoLocation extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'geoip';
    }
}
