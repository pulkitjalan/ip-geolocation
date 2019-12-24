<?php

namespace PulkitJalan\GeoIP\Drivers;

use GeoIp2\Exception\AddressNotFoundException;

abstract class MaxmindDriver extends AbstractGeoIPDriver
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
    abstract protected function create();
}
