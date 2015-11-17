<?php

namespace PulkitJalan\GeoIP\Drivers;

use GuzzleHttp\Exception\RequestException;

class TelizeDriver extends AbstractGeoIPDriver
{
    /**
     * Get array of data using telize.
     *
     * @param string $ip
     *
     * @return array
     */
    public function get($ip)
    {

        try {
            $data = json_decode($this->guzzle->get($this->getUrl($ip), [
                'headers' => [
                                'X-Mashape-Key' => array_get($this->config, 'key'),
                                'Accept' => 'application/json'
                            ]
            ])->getBody(), true);
        } catch (RequestException $e) {
            return [];
        }

        return [
            'city'        => array_get($data, 'city'),
            'country'     => array_get($data, 'country'),
            'countryCode' => array_get($data, 'country_code'),
            'latitude'    => array_get($data, 'latitude'),
            'longitude'   => array_get($data, 'longitude'),
            'region'      => array_get($data, 'region'),
            'regionCode'  => array_get($data, 'region_code'),
            'timezone'    => array_get($data, 'timezone'),
            'postalCode'  => array_get($data, 'postal_code'),
            'isp'         => array_get($data, 'organization'), // unsure what to return here; previously it was 'isp', but that does not exist anylonger.
        ];
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
        $protocol = 'http:';
        if (array_get($this->config, 'secure', false)) {
            $protocol = 'https:';
        }

        $baseUrl = $protocol.'//telize-v1.p.mashape.com/geoip/';

        return $baseUrl.$ip;
    }
}
