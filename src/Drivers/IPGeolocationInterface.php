<?php

namespace PulkitJalan\IPGeoLocation\Drivers;

interface IPGeolocationInterface
{
    /**
     * Get IPGeoLocation info from IP.
     *
     * @param  string  $ip
     * @return array
     */
    public function get($ip);

    /**
     * Get the raw IPGeoLocation info from the driver.
     *
     * @param  string  $ip
     * @return mixed
     */
    public function getRaw($ip);
}
