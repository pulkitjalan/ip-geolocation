<?php

namespace PulkitJalan\GeoIP\Tests;

use BadMethodCallException;
use PulkitJalan\GeoIP\GeoIP;
use PHPUnit\Framework\TestCase;
use PulkitJalan\GeoIP\Exceptions\InvalidDriverException;

class GeoIPTest extends TestCase
{
    protected $multipleIps = '81.2.69.160,127.0.0.1';
    protected $validIp = '81.2.69.160';
    protected $invalidIp = '127.0.0.1';

    public function test_invalid_driver_exception()
    {
        $this->expectException(InvalidDriverException::class);

        $geoip = new GeoIP([]);
    }

    public function test_bad_method_call_exception()
    {
        $this->expectException(BadMethodCallException::class);

        $geoip = new GeoIP();

        $geoip->setNothing();
    }
}
