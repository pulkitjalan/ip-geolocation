<?php

namespace PulkitJalan\IPGeolocation\Drivers;

use GuzzleHttp\Client as GuzzleClient;

abstract class AbstractIPGeolocationDriver
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var \GuzzleHttp\Client
     */
    protected $guzzle;

    public function __construct(array $config, ?GuzzleClient $guzzle = null)
    {
        $this->config = $config;

        $this->guzzle = $guzzle ?? new GuzzleClient;
    }

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
