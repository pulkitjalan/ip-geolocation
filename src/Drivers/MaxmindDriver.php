<?php

namespace PulkitJalan\GeoIP\Drivers;

use PulkitJalan\GeoIP\Exceptions\InvalidCredentialsException;
use GeoIp2\Exception\AddressNotFoundException;
use GeoIp2\WebService\Client;
use GeoIp2\Database\Reader;

class MaxmindDriver extends AbstractGeoIPDriver
{
    /**
     * @var \GeoIp2\WebService\Client|\GeoIp2\Database\Reader
     */
    protected $maxmind;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        parent::__construct($config);

        $this->maxmind = $this->create();
    }

    /**
     * Get array of data using Maxmind
     *
     * @param  string $ip
     * @return array
     */
    public function get($ip)
    {
        try {
            $data = $this->maxmind->city($ip);
        } catch (AddressNotFoundException $e) {
            return [];
        }

        return [
            'city' => $data->city->name,
            'country' => $data->country->name,
            'countryCode' => $data->country->isoCode,
            'latitude' => $data->location->latitude,
            'longitude' => $data->location->longitude,
            'region' => $data->mostSpecificSubdivision->name,
            'regionCode' => $data->mostSpecificSubdivision->isoCode,
            'timezone' => $data->location->timeZone,
            'postalCode' => $data->postal->code,
        ];
    }

    /**
     * Create the maxmind driver based on config
     *
     * @return mixed
     * @throws \PulkitJalan\GeoIP\Exceptions\InvalidCredentialsException
     */
    protected function create()
    {
        if (array_get($this->config, 'user_id', false)) {
            return $this->createWebClient();
        }

        if (array_get($this->config, 'database', false)) {
            return $this->createDatabase();
        }

        throw new InvalidCredentialsException();
    }

    /**
     * Create the maxmind web client
     *
     * @return \GeoIp2\WebService\Client
     * @throws \PulkitJalan\GeoIP\Exceptions\InvalidCredentialsException
     */
    protected function createWebClient()
    {
        $userId  = array_get($this->config, 'user_id', false);
        $licenseKey  = array_get($this->config, 'license_key', false);

        // check and make sure they are set
        if (!$userId || !$licenseKey) {
            throw new InvalidCredentialsException();
        }

        return new Client($userId, $licenseKey);
    }

    /**
     * Create the maxmind database reader
     *
     * @return \GeoIp2\Database\Reader
     * @throws \PulkitJalan\GeoIP\Exceptions\InvalidCredentialsException
     */
    protected function createDatabase()
    {
        $database = array_get($this->config, 'database', false);

        // check if file exists first
        if (!$database || !file_exists($database)) {
            throw new InvalidCredentialsException();
        }

        return new Reader($database);
    }
}
