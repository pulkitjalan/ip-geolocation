<?php

namespace PulkitJalan\GeoIP\Drivers;

use Illuminate\Support\Arr;
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

        if (! Arr::get($this->config, 'key')) {
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

        if (empty($data) || (Arr::get($data, 'latitude') === 0 && Arr::get($data, 'longitude') === 0)) {
            return $this->getDefault();
        }

        return [
            'city' => Arr::get($data, 'city'),
            'country' => Arr::get($data, 'country_name'),
            'countryCode' => Arr::get($data, 'country_code'),
            'latitude' => (float) number_format(Arr::get($data, 'latitude'), 5),
            'longitude' => (float) number_format(Arr::get($data, 'longitude'), 5),
            'region' => Arr::get($data, 'region_name'),
            'regionCode' => Arr::get($data, 'region_code'),
            'timezone' => Arr::get($data, 'time_zone.id'),
            'postalCode' => Arr::get($data, 'zip'),
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
        $protocol = 'http'.(Arr::get($this->config, 'secure', true) ? 's' : '');

        return $protocol.'://api.ipstack.com/'.$ip.'?access_key='.Arr::get($this->config, 'key');
    }
}
