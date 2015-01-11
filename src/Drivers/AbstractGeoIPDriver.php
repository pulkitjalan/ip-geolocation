<?php

namespace PulkitJalan\GeoIP\Drivers;

abstract class AbstractGeoIPDriver
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Get GeoIP info from IP
     *
     * @param  string $ip
     * @return array
     */
    abstract public function get($ip);
}
