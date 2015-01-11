<?php

namespace PulkitJalan\GeoIP\Drivers;

use GuzzleHttp\Client as GuzzleClient;
use PulkitJalan\Requester\Requester;

class IPApiDriver extends AbstractGeoIPDriver
{
    /**
     * @var string
     */
    protected $baseUrl = 'http://ip-api.com/json/';

    /**
     * @var \PulkitJalan\Requester\Requester
     */
    protected $requester;

    public function __construct(array $config)
    {
        parent::__construct($config);

        $this->requester = with(new Requester(new GuzzleClient()))->retry(2)->every(50);
    }

    /**
     * Get array of data using ip-api
     *
     * @param  string $ip
     * @return array
     */
    public function get($ip)
    {
        $data = $this->requester->url($this->baseUrl.$ip)->get()->json();

        if (array_get($data, 'status') === 'fail') {
            return [];
        }

        return [
            'city' => array_get($data, 'city'),
            'country' => array_get($data, 'country'),
            'countryCode' => array_get($data, 'countryCode'),
            'latitude' => array_get($data, 'lat'),
            'longitude' => array_get($data, 'lon'),
            'region' => array_get($data, 'region'),
            'regionCode' => array_get($data, 'regionName'),
            'timezone' => array_get($data, 'timezone'),
            'postalCode' => array_get($data, 'zip'),
        ];
    }
}
