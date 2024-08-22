<?php

namespace PulkitJalan\IPGeolocation\Facades;

use Illuminate\Support\Facades\Facade;
use PulkitJalan\IPGeolocation\IPGeolocation as IPGeolocationClass;

class IPGeolocation extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return IPGeolocationClass::class;
    }
}
