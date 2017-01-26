<?php

namespace PulkitJalan\GeoIP;

use PulkitJalan\GeoIP\Drivers\FreeGeoIPDriver;
use PulkitJalan\GeoIP\Drivers\IPApiDriver;
use PulkitJalan\GeoIP\Drivers\MaxmindDriver;
use PulkitJalan\GeoIP\Drivers\TelizeDriver;
use PulkitJalan\GeoIP\Exceptions\InvalidDriverException;

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
     * Get the driver based on config.
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
     * Get the freegeoip driver.
     *
     * @return \PulkitJalan\GeoIP\FreeGeoIPDriver
     */
    protected function createFreegeoipDriver(array $data)
    {
        return new FreeGeoIPDriver($data);
    }

    /**
     * Get the ip-api driver.
     *
     * @return \PulkitJalan\GeoIP\IPApiDriver
     */
    protected function createIpApiDriver(array $data)
    {
        return new IPApiDriver($data);
    }

    /**
     * Get the Maxmind driver.
     *
     * @return \PulkitJalan\GeoIP\MaxmindDriver
     */
    protected function createMaxmindDriver(array $data)
    {
        return new MaxmindDriver($data);
    }

    /**
     * Get the telize driver.
     *
     * @return \PulkitJalan\GeoIP\TelizeDriver
     */
    protected function createTelizeDriver(array $data)
    {
        return new TelizeDriver($data);
    }
}
