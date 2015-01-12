<?php

namespace PulkitJalan\GeoIP;

use PulkitJalan\GeoIP\Exceptions\InvalidDriverException;
use PulkitJalan\GeoIP\Drivers\MaxmindDriver;
use PulkitJalan\GeoIP\Drivers\IPApiDriver;

class GeoIPManager
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Get the driver based on config
     *
     * @return \PulkitJalan\GeoIP\AbstractGeoIPDriver
     */
    public function getDriver($driver = null)
    {
        $driver = ($driver) ?: array_get($this->config, 'driver', '');

        $method = 'create'.ucfirst(camel_case($driver)).'Driver';

        if (!method_exists($this, $method)) {
            throw new InvalidDriverException(sprintf('Driver [%s] not supported.', $driver));
        }

        return $this->{$method}(array_get($this->config, $driver, []));
    }

    /**
     * Get the Maxmind driver
     *
     * @return \PulkitJalan\GeoIP\MaxmindDriver
     */
    protected function createMaxmindDriver(array $data)
    {
        return new MaxmindDriver($data);
    }

    /**
     * Get the ip-api driver
     *
     * @return \PulkitJalan\GeoIP\IPApiDriver
     */
    protected function createIpApiDriver(array $data)
    {
        return new IPApiDriver($data);
    }
}
