<?php

namespace PulkitJalan\GeoIP\Drivers;

use Illuminate\Support\Arr;
use GeoIp2\WebService\Client;
use PulkitJalan\GeoIP\Exceptions\InvalidCredentialsException;

class MaxmindApiDriver extends MaxmindDriver
{
    /**
     * Create the maxmind web client.
     *
     * @throws \PulkitJalan\GeoIP\Exceptions\InvalidCredentialsException
     *
     * @return \GeoIp2\WebService\Client
     */
    protected function create()
    {
        $userId = Arr::get($this->config, 'user_id', false);
        $licenseKey = Arr::get($this->config, 'license_key', false);

        // check and make sure they are set
        if (! $userId || ! $licenseKey) {
            throw new InvalidCredentialsException();
        }

        return new Client($userId, $licenseKey);
    }
}
