<?php

namespace PulkitJalan\GeoIP\Drivers;

class IPApiDriver extends AbstractGeoIPDriver
{
    /**
     * Get array of data using ip-api.
     *
     * @param string $ip
     *
     * @return array
     */
    public function get($ip)
    {
        $data = $this->getRaw($ip);

        if (empty($data) || array_get($data, 'status') === 'fail') {
            return $this->getDefault();
        }

        return [
            'city' => array_get($data, 'city'),
            'country' => array_get($data, 'country'),
            'countryCode' => array_get($data, 'countryCode'),
            'latitude' => (float) number_format(array_get($data, 'lat'), 5),
            'longitude' => (float) number_format(array_get($data, 'lon'), 5),
            'region' => array_get($data, 'regionName'),
            'regionCode' => array_get($data, 'region'),
            'timezone' => array_get($data, 'timezone'),
            'postalCode' => array_get($data, 'zip'),
        ];
    }

    /**
     * Get the raw GeoIP info using ip-api.
     *
     * @param string $ip
     *
     * @return array
     */
    public function getRaw($ip)
    {
        return json_decode($this->guzzle->get($this->getUrl($ip))->getBody(), true);
    }

    /**
     * Get the ip-api url add key and
     * change base url if pro user.
     *
     * @param string $ip
     *
     * @return string
     */
    protected function getUrl($ip)
    {
        $protocol = 'http:';
        if (array_get($this->config, 'secure', false)) {
            $protocol = 'https:';
        }

        // default to free service
        // free service does not support https
        $baseUrl = 'http://ip-api.com/json/';
        $key = '';

        // if key is set change to pro service
        if (array_get($this->config, 'key', false)) {
            $baseUrl = $protocol.'//pro.ip-api.com/json/';
            $key = array_get($this->config, 'key');
        }

        return $baseUrl.$ip.(($key) ? '?key='.$key : '');
    }
}
