<?php

namespace PulkitJalan\IPGeoLocation;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use GuzzleHttp\Client as GuzzleClient;
use PulkitJalan\IPGeoLocation\Drivers\IPApiDriver;
use PulkitJalan\IPGeoLocation\Drivers\IpStackDriver;
use PulkitJalan\IPGeoLocation\Drivers\MaxmindApiDriver;
use PulkitJalan\IPGeoLocation\Drivers\AbstractGeoIPDriver;
use PulkitJalan\IPGeoLocation\Drivers\MaxmindDatabaseDriver;
use PulkitJalan\IPGeoLocation\Exceptions\InvalidDriverException;

class IPGeolocationManager
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var \GuzzleHttp\Client
     */
    protected $guzzle;

    /**
     * @param  array  $config
     */
    public function __construct(array $config, GuzzleClient $guzzle = null)
    {
        $this->config = $config;
        $this->guzzle = $guzzle;
    }

    /**
     * Get the driver based on config.
     *
     * @return AbstractGeoIPDriver
     */
    public function getDriver($driver = null): AbstractGeoIPDriver
    {
        $driver = $driver ?? Arr::get($this->config, 'driver', '');

        $method = 'create'.ucfirst(Str::camel($driver)).'Driver';

        if (! method_exists($this, $method)) {
            throw new InvalidDriverException(sprintf('Driver [%s] not supported.', $driver));
        }

        return $this->{$method}(Arr::get($this->config, $driver, []));
    }

    /**
     * Get the ip-api driver.
     *
     * @return IPApiDriver
     */
    protected function createIpApiDriver(array $data): IPApiDriver
    {
        return new IPApiDriver($data, $this->guzzle);
    }

    /**
     * Get the Maxmind driver.
     *
     * @return MaxmindDatabaseDriver
     */
    protected function createMaxmindDatabaseDriver(array $data): MaxmindDatabaseDriver
    {
        return new MaxmindDatabaseDriver($data, $this->guzzle);
    }

    /**
     * Get the Maxmind driver.
     *
     * @return MaxmindApiDriver
     */
    protected function createMaxmindApiDriver(array $data): MaxmindApiDriver
    {
        return new MaxmindApiDriver($data, $this->guzzle);
    }

    /**
     * Get the ip stack driver.
     *
     * @return IpStackDriver
     */
    protected function createIpStackDriver(array $data): IpStackDriver
    {
        return new IpStackDriver($data, $this->guzzle);
    }
}
