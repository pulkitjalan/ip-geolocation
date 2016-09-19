<?php

namespace PulkitJalan\GeoIP\Drivers;

use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;
use GeoIp2\WebService\Client;
use PulkitJalan\GeoIP\Exceptions\InvalidCredentialsException;
use PulkitJalan\GeoIP\Exceptions\InvalidDatabaseException;

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
    public function get($ip, $locale = null)
    {
        $data = $this->getRaw($ip);

        if (empty($data)) {
            return $this->getDefault();
        }
        $array_geo = [
            'city' => $this->getLocaleName($data, 'city', $locale),
            'country' => $this->getLocaleName($data, 'country', $locale),
            'countryCode' => $data->country->isoCode,
            'latitude' => (float) number_format($data->location->latitude, 5),
            'longitude' => (float) number_format($data->location->longitude, 5),
            'region' => $this->getLocaleName($data, 'mostSpecificSubdivision', $locale),
            'regionCode' => $data->mostSpecificSubdivision->isoCode,
            'timezone' => $data->location->timeZone,
            'postalCode' => $data->postal->code,
        ];

        return $array_geo;
    }

    /**
     * Get the raw GeoIP info using Maxmind.
     *
     * @param  string $ip
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
     * get name by locale.
     * @author Guixing Bai
     * @param  object $data     [description]
     * @param  string $property [description]
     * @return string           [description]
     */
    protected function getLocaleName($data, $property, $locale)
    {
        $name = $data->{$property}->name;
        $names = $data->{$property}->names;
        if (! is_null($names) && array_key_exists($locale, $names)) {
            $name = $names[$locale];
        }

        return $name;
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
        if (array_get($this->config, 'user_id', false)) {
            return $this->createWebClient();
        }

        // if database file is set then use database service
        if (array_get($this->config, 'database', false)) {
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
        $userId = array_get($this->config, 'user_id', false);
        $licenseKey = array_get($this->config, 'license_key', false);

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
        $database = array_get($this->config, 'database', false);

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
