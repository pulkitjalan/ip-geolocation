<?php

namespace PulkitJalan\IPGeolocation\Drivers;

use Illuminate\Support\Arr;
use GuzzleHttp\Client as GuzzleClient;
use PulkitJalan\IPGeolocation\Exceptions\InvalidCredentialsException;

class IPInfoDriver extends AbstractIPGeolocationDriver implements IPGeolocationInterface
{
    /**
     * @param array $config
     * @param GuzzleClient|null $guzzle
     * @throws InvalidCredentialsException
     */
    public function __construct(array $config, GuzzleClient $guzzle = null)
    {
        parent::__construct($config, $guzzle);

        if (! Arr::get($this->config, 'token')) {
            throw new InvalidCredentialsException('The IPInfo access token is required.');
        }
    }

    /**
     * Get array of data using IPInfo.
     *
     * @param string $ip
     * @return array
     */
    public function get($ip)
    {
        $data = $this->getRaw($ip);

        if (empty($data)) {
            return $this->getDefault();
        }

        return [
            'city' => Arr::get($data, 'city'),
            'country' => Arr::get($data, 'country'),
            'countryCode' => Arr::get($data, 'country'),
            'latitude' => (float) explode(',', Arr::get($data, 'loc', '0,0'))[0],
            'longitude' => (float) explode(',', Arr::get($data, 'loc', '0,0'))[1],
            'region' => Arr::get($data, 'region'),
            'regionCode' => Arr::get($data, 'region'),
            'timezone' => Arr::get($data, 'timezone'),
            'postalCode' => Arr::get($data, 'postal'),
        ];
    }

    /**
     * Get the raw IPGeolocation info using IPInfo.
     *
     * @param string $ip
     * @return array
     */
    public function getRaw($ip)
    {
        $url = "https://ipinfo.io/{$ip}?token=" . Arr::get($this->config, 'token');

        try {
            $response = $this->guzzle->get($url);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            return [];
        }
    }
}
