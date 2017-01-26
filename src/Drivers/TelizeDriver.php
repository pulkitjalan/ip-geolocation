<?php

namespace PulkitJalan\GeoIP\Drivers;

use GuzzleHttp\Exception\RequestException;
use PulkitJalan\GeoIP\Exceptions\InvalidCredentialsException;

class TelizeDriver extends AbstractGeoIPDriver
{
    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        parent::__construct($config);

        if (!array_get($this->config, 'key')) {
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
            'city' => array_get($data, 'city'),
            'country' => array_get($data, 'country'),
            'countryCode' => array_get($data, 'country_code'),
            'latitude' => (float) number_format(array_get($data, 'latitude'), 5),
            'longitude' => (float) number_format(array_get($data, 'longitude'), 5),
            'region' => array_get($data, 'region'),
            'regionCode' => array_get($data, 'region_code'),
            'timezone' => array_get($data, 'timezone'),
            'postalCode' => array_get($data, 'postal_code'),
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
        try {
            return json_decode($this->guzzle->get($this->getUrl($ip), [
                'headers' => [
                    'X-Mashape-Key' => array_get($this->config, 'key'),
                    'Accept' => 'application/json',
                ],
            ])->getBody(), true);
        } catch (RequestException $e) {
            // ignore
        }

        return [];
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
