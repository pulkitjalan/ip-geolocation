<?php

namespace PulkitJalan\IPGeolocation\Drivers;

use Illuminate\Support\Arr;
use GuzzleHttp\Client as GuzzleClient;
use PulkitJalan\IPGeolocation\Exceptions\InvalidCredentialsException;

class IP2LocationDriver extends AbstractIPGeolocationDriver implements IPGeolocationInterface
{
    /**
     * @throws InvalidCredentialsException
     */
    public function __construct(array $config, ?GuzzleClient $guzzle = null)
    {
        parent::__construct($config, $guzzle);

        if (! Arr::get($this->config, 'api_key')) {
            throw new InvalidCredentialsException('IP2Location API key is required');
        }
    }

    /**
     * Get array of data using IP2Location.
     *
     * @param  string  $ip
     * @return array
     */
    public function get($ip)
    {
        $data = $this->getRaw($ip);

        if (empty($data) || Arr::get($data, 'response') !== 'OK') {
            return $this->getDefault();
        }

        return [
            'city' => Arr::get($data, 'city_name'),
            'country' => Arr::get($data, 'country_name'),
            'countryCode' => Arr::get($data, 'country_code'),
            'latitude' => (float) number_format(Arr::get($data, 'latitude', 0), 5),
            'longitude' => (float) number_format(Arr::get($data, 'longitude', 0), 5),
            'region' => Arr::get($data, 'region_name'),
            'regionCode' => Arr::get($data, 'region_code'),
            'timezone' => Arr::get($data, 'time_zone'),
            'postalCode' => Arr::get($data, 'zip_code'),
        ];
    }

    /**
     * Get the raw IP2Location info.
     *
     * @param  string  $ip
     * @return array
     */
    public function getRaw($ip)
    {
        $url = $this->getUrl($ip);
        $response = $this->guzzle->get($url);

        return json_decode($response->getBody(), true);
    }

    /**
     * Get the IP2Location API URL.
     *
     * @param  string  $ip
     * @return string
     */
    protected function getUrl($ip)
    {
        $apiKey = Arr::get($this->config, 'api_key');

        return "https://api.ip2location.io/?key={$apiKey}&ip={$ip}&format=json";
    }
}
