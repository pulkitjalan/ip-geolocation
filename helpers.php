<?php

use PulkitJalan\IPGeolocation\IPGeolocation;

if (! function_exists('ipGeolocation')) {
    /**
     * Get an instance of the current ip geolocation.
     *
     * @return \PulkitJalan\IPGeolocation\IPGeolocation
     */
    function ipGeolocation($key = null)
    {
        if (is_null($key)) {
            return app(IPGeolocation::class);
        }

        return app(IPGeolocation::class)->{'get'.ucwords(camel_case($key))}();
    }
}
