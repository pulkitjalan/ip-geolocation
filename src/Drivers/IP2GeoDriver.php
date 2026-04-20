<?php

namespace PulkitJalan\IPGeolocation\Drivers;

use Illuminate\Support\Arr;
use GuzzleHttp\Client as GuzzleClient;

class IP2GeoDriver extends AbstractIPGeolocationDriver implements IPGeolocationInterface
{
    /**
     * Get array of data using ip2geo.
     *
     * @param  string  $ip
     * @return array
     */
    public function get($ip)
    {
        $data = $this->getRaw($ip);

        if (empty($data) || ! Arr::get($data, 'success')) {
            return $this->getDefault();
        }

        $d = Arr::get($data, 'data', []);
        $continent = Arr::get($d, 'continent', []);
        $country = Arr::get($continent, 'country', []);
        $city = Arr::get($country, 'city', []);
        $subdivision = Arr::get($country, 'subdivision', []);
        $timezone = Arr::get($city, 'timezone', []);

        return [
            'city' => Arr::get($city, 'name'),
            'country' => Arr::get($country, 'name'),
            'countryCode' => Arr::get($country, 'code'),
            'latitude' => Arr::get($city, 'latitude') !== null
                ? (float) number_format(Arr::get($city, 'latitude'), 5)
                : null,
            'longitude' => Arr::get($city, 'longitude') !== null
                ? (float) number_format(Arr::get($city, 'longitude'), 5)
                : null,
            'region' => Arr::get($subdivision, 'name'),
            'regionCode' => Arr::get($subdivision, 'code'),
            'timezone' => Arr::get($timezone, 'name'),
            'postalCode' => Arr::get($city, 'postal_code'),
        ];
    }

    /**
     * Get the raw IPGeolocation info using ip2geo.
     *
     * @param  string  $ip
     * @return array
     */
    public function getRaw($ip)
    {
        $url = 'https://api.ip2geo.dev/convert?ip=' . urlencode($ip);

        try {
            return json_decode(
                $this->guzzle->get($url, [
                    'headers' => [
                        'X-Api-Key' => Arr::get($this->config, 'key'),
                    ],
                ])->getBody(),
                true
            );
        } catch (\Throwable $e) {
            return [];
        }
    }
}
