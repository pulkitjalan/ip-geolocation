<?php

namespace PulkitJalan\GeoIP\Drivers;

use Illuminate\Support\Arr;

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

        if (empty($data) || (Arr::get($data, 'status') === 'fail')) {
            return $this->getDefault();
        }

        return [
            'city' => Arr::get($data, 'city'),
            'country' => Arr::get($data, 'country'),
            'countryCode' => Arr::get($data, 'countryCode'),
            'latitude' => (float) number_format(Arr::get($data, 'lat'), 5),
            'longitude' => (float) number_format(Arr::get($data, 'lon'), 5),
            'region' => Arr::get($data, 'regionName'),
            'regionCode' => Arr::get($data, 'region'),
            'timezone' => Arr::get($data, 'timezone'),
            'postalCode' => Arr::get($data, 'zip'),
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
        // default to free service
        // free service does not support https
        $baseUrl = 'http://ip-api.com/json/';
        $key = '';

        // if key is set change to pro service
        if (Arr::get($this->config, 'key', false)) {
            $baseUrl = 'https://pro.ip-api.com/json/';
            $key = Arr::get($this->config, 'key');
        }

        return $baseUrl.$ip.(($key) ? '?key='.$key : '');
    }
}
