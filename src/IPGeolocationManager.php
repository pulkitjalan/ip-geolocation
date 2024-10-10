<?php

namespace PulkitJalan\IPGeolocation;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use GuzzleHttp\Client as GuzzleClient;
use PulkitJalan\IPGeolocation\Drivers\IPApiDriver;
use PulkitJalan\IPGeolocation\Drivers\IPStackDriver;
use PulkitJalan\IPGeolocation\Drivers\MaxmindApiDriver;
use PulkitJalan\IPGeolocation\Drivers\IP2LocationDriver;
use PulkitJalan\IPGeolocation\Drivers\MaxmindDatabaseDriver;
use PulkitJalan\IPGeolocation\Exceptions\InvalidDriverException;
use PulkitJalan\IPGeolocation\Drivers\AbstractIPGeolocationDriver;

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
     * @return AbstractIPGeolocationDriver
     */
    public function getDriver($driver = null): AbstractIPGeolocationDriver
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
     * @return IPStackDriver
     */
    protected function createIpStackDriver(array $data): IPStackDriver
    {
        return new IPStackDriver($data, $this->guzzle);
    }

    /**
     * Get the IP2Location driver.
     *
     * @return IP2LocationDriver
     */
    protected function createIp2locationDriver(array $data): IP2LocationDriver
    {
        return new IP2LocationDriver($data, $this->guzzle);
    }
}
