<?php

namespace PulkitJalan\IPGeoLocation\Drivers;

use Illuminate\Support\Arr;
use GeoIp2\WebService\Client;
use PulkitJalan\IPGeoLocation\Exceptions\InvalidCredentialsException;

class MaxmindApiDriver extends MaxmindDriver
{
    /**
     * Create the maxmind web client.
     *
     * @return \GeoIp2\WebService\Client
     *
     * @throws \PulkitJalan\IPGeoLocation\Exceptions\InvalidCredentialsException
     */
    protected function create()
    {
        $userId = Arr::get($this->config, 'user_id', false);
        $licenseKey = Arr::get($this->config, 'license_key', false);
        $host = Arr::get($this->config, 'host');
        $locales = Arr::get($this->config, 'locales', ['en']);

        // check and make sure they are set
        if (! $userId || ! $licenseKey) {
            throw new InvalidCredentialsException();
        }

        return new Client((int) $userId, $licenseKey, $locales, ['host' => $host]);
    }
}
