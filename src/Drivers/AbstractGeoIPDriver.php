<?php

namespace PulkitJalan\GeoIP\Drivers;

use GuzzleHttp\Client as GuzzleClient;
use PulkitJalan\Requester\Requester;

abstract class AbstractGeoIPDriver
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var \PulkitJalan\Requester\Requester
     */
    protected $requester;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;

        $this->requester = with(new Requester(new GuzzleClient()))->retry(2)->every(50);
    }

    /**
     * Get GeoIP info from IP
     *
     * @param  string $ip
     * @return array
     */
    abstract public function get($ip);
}
