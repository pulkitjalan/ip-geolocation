<?php

namespace PulkitJalan\GeoIP\Drivers;

use GuzzleHttp\Client as GuzzleClient;
use PulkitJalan\Requester\Requester;

class IPApiDriver extends AbstractGeoIPDriver
{
    /**
     * @var string
     */
    protected $key;

    /**
     * @var string
     */
    protected $baseUrl = 'http://ip-api.com/json/';

    /**
     * @var \PulkitJalan\Requester\Requester
     */
    protected $requester;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        parent::__construct($config);

        $this->requester = $this->create();
    }

    /**
     * Get array of data using ip-api
     *
     * @param  string $ip
     * @return array
     */
    public function get($ip)
    {
        $data = $this->requester->url($this->getUrl($ip))->get()->json();

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

    protected function getUrl($ip)
    {
        return $this->baseUrl.$ip.(($this->key) ? '?key='.$this->key : '');
    }

    /**
     * Create the ip-api driver based on config
     *
     * @return mixed
     */
    protected function create()
    {
        if (array_get($this->config, 'key', false)) {
            $this->baseUrl = 'http://pro.ip-api.com/json/';
            $this->key = array_get($this->config, 'key');
        }

        return with(new Requester(new GuzzleClient()))->retry(2)->every(50);
    }
}
