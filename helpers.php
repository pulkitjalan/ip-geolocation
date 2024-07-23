<?php

if (! function_exists('geoip')) {
    /**
     * Get an instance of the current geoip.
     *
     * @return \PulkitJalan\IPGeoLocation\IPGeoLocation
     */
    function geoip($key = null)
    {
        if (is_null($key)) {
            return app('geoip');
        }

        return app('geoip')->{'get'.ucwords(camel_case($key))}();
    }
}
