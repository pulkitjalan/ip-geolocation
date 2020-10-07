<?php

namespace PulkitJalan\GeoIP\Drivers;

use Illuminate\Support\Arr;
use GuzzleHttp\Client as GuzzleClient;
use PulkitJalan\GeoIP\Exceptions\InvalidCredentialsException;

class TelizeDriver extends AbstractGeoIPDriver
{
    /**
     * @param array $config
     */
    public function __construct(array $config, GuzzleClient $guzzle = null)
    {
        parent::__construct($config, $guzzle);

        if (! Arr::get($this->config, 'key')) {
            throw new InvalidCredentialsException();
        }
    }

    /**
     * Get array of data using telize.
     *
     * @param string $ip
     *
     * @return array
     */
    public function get($ip)
    {
        $data = $this->getRaw($ip);

        if (empty($data)) {
            return $this->getDefault();
        }

        return [
            'city' => Arr::get($data, 'city'),
            'country' => Arr::get($data, 'country'),
            'countryCode' => Arr::get($data, 'country_code'),
            'latitude' => (float) number_format(Arr::get($data, 'latitude'), 5),
            'longitude' => (float) number_format(Arr::get($data, 'longitude'), 5),
            'region' => Arr::get($data, 'region'),
            'regionCode' => Arr::get($data, 'region_code'),
            'timezone' => Arr::get($data, 'timezone'),
            'postalCode' => Arr::get($data, 'postal_code'),
        ];
    }

    /**
     * Get the raw GeoIP info using telize.
     *
     * @param string $ip
     *
     * @return array
     */
    public function getRaw($ip)
    {
        return json_decode($this->guzzle->get($this->getUrl($ip), [
            'headers' => [
                'X-Mashape-Key' => Arr::get($this->config, 'key'),
                'Accept' => 'application/json',
            ],
        ])->getBody(), true);
    }

    /**
     * Get the telize url.
     *
     * @param string $ip
     *
     * @return string
     */
    protected function getUrl($ip)
    {
        return 'https://telize-v1.p.mashape.com/geoip/'.$ip;
    }
}
