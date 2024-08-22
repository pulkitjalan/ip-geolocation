<?php

namespace PulkitJalan\IPGeolocation\Drivers;

interface IPGeolocationInterface
{
    /**
     * Get IPGeolocation info from IP.
     *
     * @param  string  $ip
     * @return array
     */
    public function get($ip);

    /**
     * Get the raw IPGeolocation info from the driver.
     *
     * @param  string  $ip
     * @return mixed
     */
    public function getRaw($ip);
}
