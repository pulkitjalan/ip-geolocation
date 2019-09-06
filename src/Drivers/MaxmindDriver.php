<?php

namespace PulkitJalan\GeoIP\Drivers;

use GeoIp2\Database\Reader;
use Illuminate\Support\Arr;
use GeoIp2\WebService\Client;
use GeoIp2\Exception\AddressNotFoundException;
use PulkitJalan\GeoIP\Exceptions\InvalidDatabaseException;
use PulkitJalan\GeoIP\Exceptions\InvalidCredentialsException;

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
     * Get array of data using Maxmind.
     *
     * @param string $ip
     *
     * @return array
     */
    public function get($ip)
    {
        $data = $this->getRaw($ip);

        if (empty($data)) {
            return $this->getDefault();
        }

        return [
            'city' => $data->city->name,
            'country' => $data->country->name,
            'countryCode' => $data->country->isoCode,
            'latitude' => (float) number_format($data->location->latitude, 5),
            'longitude' => (float) number_format($data->location->longitude, 5),
            'region' => $data->mostSpecificSubdivision->name,
            'regionCode' => $data->mostSpecificSubdivision->isoCode,
            'continent' => $data->continent->name,
            'continentCode' => $data->continent->code,
            'timezone' => $data->location->timeZone,
            'postalCode' => $data->postal->code,
        ];
    }

    /**
     * Get the raw GeoIP info using Maxmind.
     *
     * @param string $ip
     *
     * @return mixed
     */
    public function getRaw($ip)
    {
        try {
            return $this->maxmind->city($ip);
        } catch (AddressNotFoundException $e) {
            // ignore
        }

        return [];
    }

    /**
     * Create the maxmind driver based on config.
     *
     * @throws \PulkitJalan\GeoIP\Exceptions\InvalidCredentialsException
     *
     * @return mixed
     */
    protected function create()
    {
        // if user_id and license_key are set then use the web service
        if (Arr::get($this->config, 'user_id', false)) {
            return $this->createWebClient();
        }

        // if database file is set then use database service
        if (Arr::get($this->config, 'database', false)) {
            return $this->createDatabase();
        }

        throw new InvalidCredentialsException();
    }

    /**
     * Create the maxmind web client.
     *
     * @throws \PulkitJalan\GeoIP\Exceptions\InvalidCredentialsException
     *
     * @return \GeoIp2\WebService\Client
     */
    protected function createWebClient()
    {
        $userId = Arr::get($this->config, 'user_id', false);
        $licenseKey = Arr::get($this->config, 'license_key', false);

        // check and make sure they are set
        if (! $userId || ! $licenseKey) {
            throw new InvalidCredentialsException();
        }

        return new Client($userId, $licenseKey);
    }

    /**
     * Create the maxmind database reader.
     *
     * @throws \PulkitJalan\GeoIP\Exceptions\InvalidCredentialsException
     *
     * @return \GeoIp2\Database\Reader
     */
    protected function createDatabase()
    {
        $database = Arr::get($this->config, 'database', false);

        // check if file exists first
        if (! $database || ! file_exists($database)) {
            throw new InvalidCredentialsException();
        }

        // catch maxmind exception and throw geoip exception
        try {
            return new Reader($database);
        } catch (\MaxMind\Db\Reader\InvalidDatabaseException $e) {
            throw new InvalidDatabaseException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
