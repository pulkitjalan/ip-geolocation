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
        $data = $this->requester->url($this->getUrl($ip))->get()->json();

        if (array_get($data, 'status') === 'fail') {
            return [];
        }

        return [
            'city'         => array_get($data, 'city'),
            'country'      => array_get($data, 'country'),
            'countryCode'  => array_get($data, 'countryCode'),
            'latitude'     => array_get($data, 'lat'),
            'longitude'    => array_get($data, 'lon'),
            'region'       => array_get($data, 'region'),
            'regionCode'   => array_get($data, 'regionName'),
            'timezone'     => array_get($data, 'timezone'),
            'postalCode'   => array_get($data, 'zip'),
            'organization' => array_get($data, 'org'),
            'isp'          => array_get($data, 'isp'),
        ];
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
