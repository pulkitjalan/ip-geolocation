<?php

namespace PulkitJalan\GeoIP;

use PulkitJalan\GeoIP\Exceptions\GeoIPException;

class GeoIP
{
    /**
     * @var string
     */
    protected $ip;

    /**
     * @var \PulkitJalan\GeoIP\Contracts\GeoIPInterface
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
    public function __construct(array $config = ['driver' => 'ip-api'])
    {
        $this->driver = with(new GeoIPManager($config))->getDriver();
        $this->random = array_get($config, 'random', false);
    }

    /**
     * Getter for driver.
     *
     * @return \PulkitJalan\GeoIP\Contracts\GeoIPInterface
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
     * @param string $ip
     *
     * @return GeoIP
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
        if (!$this->ip) {
            if ($this->random) {
                $this->ip = long2ip(mt_rand());
            } else {
                $this->ip = array_get($_SERVER, 'HTTP_CLIENT_IP', array_get($_SERVER, 'HTTP_X_FORWARDED_FOR', array_get($_SERVER, 'HTTP_X_FORWARDED', array_get($_SERVER, 'HTTP_FORWARDED_FOR', array_get($_SERVER, 'HTTP_FORWARDED', array_get($_SERVER, 'REMOTE_ADDR', '127.0.0.1'))))));
            }
        }

        // sometimes we can get multiple ipaddresses
        // seperated with a ',', eg: proxy/vpn
        $this->ip = explode(',', $this->ip);
        $this->ip = trim(head($this->ip));

        return $this->ip;
    }

    /**
     * Get an array or single item of geoip data
     * Also stores data in memory for further requests.
     *
     * @param string $property
     *
     * @return array|string
     */
    public function get($property = '')
    {
        $data = $this->getData();

        if (!$property) {
            return $data;
        }

        return array_get($data, $property, '');
    }

    /**
     * Get the raw geoip data from the driver.
     *
     * @param string
     *
     * @return mixed
     */
    public function getRaw()
    {
        $ip = $this->getIp();
        $this->setIp($ip);

        // check ip in memory
        $data = array_get($this->storeRaw, $ip);

        if (!$data) {
            try {
                $data = $this->getDriver()->getRaw($ip);
            } catch (\Exception $e) {
                throw new GeoIPException('Failed to get raw geoip data', 0, $e);
            }

            // cache ip data in memory
            $this->storeRaw[$ip] = $data;
        }

        return $data;
    }

    /**
     * Get an array or single item of geoip data.
     *
     * @throws \PulkitJalan\GeoIP\Exceptions\GeoIPException
     *
     * @return array
     */
    protected function getData()
    {
        $ip = $this->getIp();
        $this->setIp($ip);

        // check ip in memory
        $data = array_get($this->store, $ip);

        if (!$data) {
            try {
                $data = $this->getDriver()->get($ip);
            } catch (\Exception $e) {
                throw new GeoIPException('Failed to get geoip data', 0, $e);
            }

            // cache ip data in memory
            $this->store[$ip] = $data;
        }

        return $data;
    }

    /**
     * Magic call method for get*.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @throws \BadMethodCallException
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (starts_with($method, 'get')) {
            $param = lcfirst(ltrim($method, 'get'));

            return $this->get($param);
        }

        throw new \BadMethodCallException(sprintf('Method [%s] does not exist.', $method));
    }
}
