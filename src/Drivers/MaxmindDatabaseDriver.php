<?php

namespace PulkitJalan\IPGeoLocation\Drivers;

use GeoIp2\Database\Reader;
use Illuminate\Support\Arr;
use PulkitJalan\IPGeoLocation\Exceptions\InvalidDatabaseException;
use PulkitJalan\IPGeoLocation\Exceptions\InvalidCredentialsException;
use MaxMind\Db\Reader\InvalidDatabaseException as MaxMindInvalidDatabaseException;

class MaxmindDatabaseDriver extends MaxmindDriver
{
    /**
     * Create the maxmind database reader.
     *
     * @return \GeoIp2\Database\Reader
     *
     * @throws \PulkitJalan\IPGeoLocation\Exceptions\InvalidCredentialsException
     */
    protected function create()
    {
        $database = Arr::get($this->config, 'database', false);

        // check if file exists first
        if (! $database || ! file_exists($database)) {
            throw new InvalidCredentialsException();
        }

        // catch maxmind exception and throw internal exception
        try {
            return new Reader($database);
        } catch (MaxMindInvalidDatabaseException $e) {
            throw new InvalidDatabaseException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
