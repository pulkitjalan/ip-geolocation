<?php

namespace PulkitJalan\GeoIP\Drivers;

class FreeGeoIPDriver extends AbstractGeoIPDriver
{
    /**
     * Get array of data using freegeoip.
     *
     * @param string $ip
     *
     * @return array
     */
    public function get($ip)
    {
        $data = $this->getRaw($ip);

        if (empty($data) || array_get($data, 'latitude') === 0 && array_get($data, 'longitude') === 0) {
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
            'timezone' => array_get($data, 'time_zone'),
            'postalCode' => array_get($data, 'zip_code'),
        ];
    }

    /**
     * Get the raw GeoIP info using freegeoip.
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
     * Get the freegeoip url.
     *
     * @param string $ip
     *
     * @return string
     */
    protected function getUrl($ip)
    {
        $protocol = 'http://';
        if (array_get($this->config, 'secure', true)) {
            $protocol = 'https://';
        }

        return $protocol.array_get($this->config, 'url', 'freegeoip.net').'/json/'.$ip;
    }
}
