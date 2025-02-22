<?php

namespace PulkitJalan\IPGeolocation\Drivers;

use Illuminate\Support\Arr;

class IPQueryDriver extends AbstractIPGeolocationDriver implements IPGeolocationInterface
{
    /**
     * Get array of data using IPQuery.
     *
     * @param  string  $ip
     * @return array
     */
    public function get($ip)
    {
        $data = $this->getRaw($ip);

        if (empty($data) || isset($data['error'])) {
            return $this->getDefault();
        }

        return [
            'city' => Arr::get($data, 'location.city'),
            'country' => Arr::get($data, 'location.country'),
            'countryCode' => Arr::get($data, 'location.country_code'),
            'latitude' => (float) number_format(Arr::get($data, 'location.latitude'), 5),
            'longitude' => (float) number_format(Arr::get($data, 'location.longitude'), 5),
            'region' => Arr::get($data, 'location.state'),
            'regionCode' => null, // IPQuery doesn't provide region code
            'timezone' => Arr::get($data, 'location.timezone'),
            'postalCode' => Arr::get($data, 'location.zipcode'),
        ];
    }

    /**
     * Get the raw IPGeolocation info using IPQuery.
     *
     * @param  string  $ip
     * @return array
     */
    public function getRaw($ip)
    {
        return json_decode($this->guzzle->get($this->getUrl($ip))->getBody(), true);
    }

    /**
     * Get the IPQuery API URL with API key.
     *
     * @param  string  $ip
     * @return string
     */
    protected function getUrl($ip)
    {
        $baseUrl = 'https://api.ipquery.io/';
        $key = Arr::get($this->config, 'key');

        if (empty($key)) {
            throw new \InvalidArgumentException('IPQuery API key is required');
        }

        return $baseUrl . $ip . '?api_key=' . $key;
    }
}