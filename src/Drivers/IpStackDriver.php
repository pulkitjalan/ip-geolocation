<?php

namespace PulkitJalan\GeoIP\Drivers;

use GuzzleHttp\Exception\RequestException;
use PulkitJalan\GeoIP\Exceptions\InvalidCredentialsException;

class IpStackDriver extends AbstractGeoIPDriver
{
    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        parent::__construct($config);

        if (! array_get($this->config, 'key')) {
            throw new InvalidCredentialsException();
        }
    }

    /**
     * Get array of data using ipstack.
     *
     * @param string $ip
     *
     * @return array
     */
    public function get($ip)
    {
        $data = $this->getRaw($ip);

        if (empty($data) || (array_get($data, 'latitude') === 0 && array_get($data, 'longitude') === 0)) {
            return $this->getDefault();
        }

        return [
            'city' => array_get($data, 'city'),
            'country' => array_get($data, 'country_name'),
            'countryCode' => array_get($data, 'country_code'),
            'latitude' => (float) number_format(array_get($data, 'latitude'), 5),
            'longitude' => (float) number_format(array_get($data, 'longitude'), 5),
            'region' => array_get($data, 'region_name'),
            'regionCode' => array_get($data, 'region_code'),
            'timezone' => array_get($data, 'time_zone.id'),
            'postalCode' => array_get($data, 'zip'),
        ];
    }

    /**
     * Get the raw GeoIP info using ipstack.
     *
     * @param string $ip
     *
     * @return array
     */
    public function getRaw($ip)
    {
        try {
            return json_decode($this->guzzle->get($this->getUrl($ip))->getBody(), true);
        } catch (RequestException $e) {
            // ignore
        }

        return [];
    }

    /**
     * Get the ipstack url.
     *
     * @param string $ip
     *
     * @return string
     */
    protected function getUrl($ip)
    {
        return 'https://api.ipstack.com/'.$ip.'?access_key='.array_get($this->config, 'key');
    }
}
