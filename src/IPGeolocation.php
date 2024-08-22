<?php

namespace PulkitJalan\IPGeolocation;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use GuzzleHttp\Client as GuzzleClient;
use PulkitJalan\IPGeolocation\Exceptions\IPGeolocationException;

class IPGeolocation
{
    /**
     * @var string
     */
    protected $ip;

    /**
     * @var \PulkitJalan\IPGeolocation\Drivers\IPGeolocationInterface
     */
    protected $driver;

    /**
     * @var bool
     */
    protected $random;

    /**
     * @var array
     */
    protected $store = [];

    /**
     * @var array
     */
    protected $storeRaw = [];

    /**
     * @var array
     */
    public function __construct(array $config = ['driver' => 'ip-api'], GuzzleClient $guzzle = null)
    {
        $this->driver = (new IPGeolocationManager($config, $guzzle))->getDriver();
        $this->random = Arr::get($config, 'random', false);
    }

    /**
     * Getter for driver.
     *
     * @return \PulkitJalan\IPGeolocation\Drivers\IPGeolocationInterface
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * Set ip.
     *
     * @var string
     *
     * @param  string  $ip
     * @return IPGeolocation
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip from server info.
     *
     * @return string ipaddress
     */
    public function getIp()
    {
        if (! $this->ip) {
            if ($this->random) {
                $this->ip = long2ip(mt_rand());
            } else {
                $this->ip = Arr::get($_SERVER, 'HTTP_CLIENT_IP', Arr::get($_SERVER, 'HTTP_X_FORWARDED_FOR', Arr::get($_SERVER, 'HTTP_X_FORWARDED', Arr::get($_SERVER, 'HTTP_FORWARDED_FOR', Arr::get($_SERVER, 'HTTP_FORWARDED', Arr::get($_SERVER, 'REMOTE_ADDR', '127.0.0.1'))))));
            }
        }

        // sometimes we can get multiple ipaddresses
        // seperated with a ',', eg: proxy/vpn
        $this->ip = explode(',', $this->ip);
        $this->ip = trim(head($this->ip));

        return $this->ip;
    }

    /**
     * Get an array or single item of ip geolocation data
     * Also stores data in memory for further requests.
     *
     * @param  string  $property
     * @return array|string
     */
    public function get($property = '')
    {
        $data = $this->getData();

        if (! $property) {
            return $data;
        }

        return Arr::get($data, $property, '');
    }

    /**
     * Get the raw ip geolocation data from the driver.
     *
     * @param string
     * @return mixed
     */
    public function getRaw()
    {
        $ip = $this->getIp();
        $this->setIp($ip);

        // check ip in memory
        $data = Arr::get($this->storeRaw, $ip);

        if (! $data) {
            try {
                $data = $this->getDriver()->getRaw($ip);
            } catch (\Exception $e) {
                throw new IPGeolocationException('Failed to get raw ip geolocation data', 0, $e);
            }

            // cache ip data in memory
            $this->storeRaw[$ip] = $data;
        }

        return $data;
    }

    /**
     * Get an array or single item of ipGeolocation data.
     *
     * @return array
     *
     * @throws \PulkitJalan\IPGeolocation\Exceptions\IPGeolocationException
     */
    protected function getData()
    {
        $ip = $this->getIp();
        $this->setIp($ip);

        // check ip in memory
        $data = Arr::get($this->store, $ip);

        if (! $data) {
            try {
                $data = $this->getDriver()->get($ip);
            } catch (\Exception $e) {
                throw new IPGeolocationException('Failed to get ip geolocation data', 0, $e);
            }

            // cache ip data in memory
            $this->store[$ip] = $data;
        }

        return $data;
    }

    /**
     * Magic call method for get*.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     *
     * @throws \BadMethodCallException
     */
    public function __call($method, $parameters)
    {
        if (Str::startsWith($method, 'get')) {
            $param = lcfirst(ltrim($method, 'get'));

            return $this->get($param);
        }

        throw new \BadMethodCallException(sprintf('Method [%s] does not exist.', $method));
    }
}
