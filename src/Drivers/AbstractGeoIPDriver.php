<?php

namespace PulkitJalan\GeoIP\Drivers;

use GuzzleHttp\Client as GuzzleClient;

abstract class AbstractGeoIPDriver
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var \GuzzleHttp\Client
     */
    protected $guzzle;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;

        $this->guzzle = new GuzzleClient();
    }

    /**
     * Get GeoIP info from IP.
     *
     * @param string $ip
     *
     * @return array
     */
    abstract public function get($ip);
}
