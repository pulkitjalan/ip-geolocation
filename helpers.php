<?php

if (! function_exists('ipGeolocation')) {
    /**
     * Get an instance of the current ip geolocation.
     *
     * @return \PulkitJalan\IPGeoLocation\IPGeoLocation
     */
    function ipGeolocation($key = null)
    {
        if (is_null($key)) {
            return app('ipGeolocation');
        }

        return app('ipGeolocation')->{'get'.ucwords(camel_case($key))}();
    }
}
