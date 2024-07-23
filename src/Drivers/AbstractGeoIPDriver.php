<?php

namespace PulkitJalan\IPGeoLocation\Drivers;

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
     * @param  array  $config
     */
    public function __construct(array $config, GuzzleClient $guzzle = null)
    {
        $this->config = $config;

        $this->guzzle = $guzzle ?? new GuzzleClient();
    }

    /**
     * Get IPGeoLocation info from IP.
     *
     * @param  string  $ip
     * @return array
     */
    abstract public function get($ip);

    /**
     * Get the raw IPGeoLocation info from the driver.
     *
     * @param  string  $ip
     * @return mixed
     */
    abstract public function getRaw($ip);

    /**
     * Get the default values (all null).
     *
     * @return array
     */
    protected function getDefault()
    {
        return [
            'city' => null,
            'country' => null,
            'countryCode' => null,
            'latitude' => null,
            'longitude' => null,
            'region' => null,
            'regionCode' => null,
            'timezone' => null,
            'postalCode' => null,
        ];
    }
}
