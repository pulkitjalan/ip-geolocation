<?php

namespace PulkitJalan\GeoIP\Tests\Drivers;

use Illuminate\Support\Arr;
use PulkitJalan\GeoIP\GeoIP;
use PulkitJalan\GeoIP\Tests\AbstractTestCase;
use PulkitJalan\GeoIP\Exceptions\GeoIPException;

class IPApiDriverTest extends AbstractTestCase
{
    public function test_ip_api()
    {
        $config = [
            'driver' => 'ip-api',
        ];

        $geoip = new GeoIP($config);
        $geoip = $geoip->setIp($this->validIp);

        $this->assertEquals($geoip->getCountry(), 'United Kingdom');

        $geoip = $geoip->setIp($this->invalidIp);

        $this->assertEquals('fail', Arr::get($geoip->getRaw(), 'status'));

        $this->assertEquals([
            'city' => null,
            'country' => null,
            'countryCode' => null,
            'latitude' => null,
            'longitude' => null,
            'region' => null,
            'regionCode' => null,
            'timezone' => null,
            'postalCode' => null,
        ], $geoip->get());

        $this->assertEquals('', $geoip->getCountry());
    }

    public function test_get_multiple_ipaddress()
    {
        $config = [
            'driver' => 'ip-api',
        ];

        $geoip = new GeoIP($config);
        $geoip->setIp($this->multipleIps);
        $ip = $geoip->getIp();

        $this->assertEquals($this->validIp, $ip);
        $this->assertTrue(! (filter_var($ip, FILTER_VALIDATE_IP)) === false);
    }

    public function test_get_random_ipaddress()
    {
        $config = [
            'driver' => 'ip-api',
            'random' => true,
        ];

        $geoip = new GeoIP($config);
        $ip = $geoip->getIp();

        $this->assertNotEquals($this->invalidIp, $ip);
        $this->assertTrue(! (filter_var($ip, FILTER_VALIDATE_IP)) === false);
    }

    public function test_get_non_random_ipaddress()
    {
        $config = [
            'driver' => 'ip-api',
            'random' => false,
        ];

        $geoip = new GeoIP($config);
        $ip = $geoip->getIp();

        $this->assertEquals($this->invalidIp, $ip);
        $this->assertTrue(! (filter_var($ip, FILTER_VALIDATE_IP)) === false);
    }

    public function test_ip_api_pro_exception()
    {
        $config = [
            'driver' => 'ip-api',
            'ip-api' => [
                'key' => 'test',
            ],
        ];

        $this->expectException(GeoIPException::class);

        $geoip = new GeoIP($config);
        $geoip = $geoip->setIp($this->validIp);

        $geoip->get();
    }

    public function test_ip_api_pro_exception_getRaw()
    {
        $config = [
            'driver' => 'ip-api',
            'ip-api' => [
                'key' => 'test',
            ],
        ];

        $this->expectException(GeoIPException::class);

        $geoip = new GeoIP($config);
        $geoip = $geoip->setIp($this->validIp);

        $geoip->getRaw();
    }
}
