<?php

namespace PulkitJalan\geoip\tests;

use Mockery;
use BadMethodCallException;
use Illuminate\Support\Arr;
use PulkitJalan\GeoIP\GeoIP;
use PHPUnit\Framework\TestCase;
use PulkitJalan\GeoIP\Exceptions\GeoIPException;
use PulkitJalan\GeoIP\Exceptions\InvalidDriverException;
use PulkitJalan\GeoIP\Exceptions\InvalidDatabaseException;
use PulkitJalan\GeoIP\Exceptions\InvalidCredentialsException;

class GeoIPTest extends TestCase
{
    protected $multipleIps = '81.2.69.160,127.0.0.1';
    protected $validIp = '81.2.69.160';
    protected $invalidIp = '127.0.0.1';

    public function tearDown(): void
    {
        Mockery::close();
    }

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

    public function test_maxmind_exception()
    {
        $this->expectException(InvalidCredentialsException::class);

        $geoip = new GeoIP(['driver' => 'maxmind']);
    }

    public function test_maxmind_database_exception()
    {
        $config = [
            'driver' => 'maxmind',
            'maxmind' => [
                'database' => __DIR__.'/data/GeoIP2-City.mmdb',
            ],
        ];

        $this->expectException(InvalidCredentialsException::class);

        $geoip = new GeoIP($config);
    }

    public function test_maxmind_invalid_database_exception()
    {
        $config = [
            'driver' => 'maxmind',
            'maxmind' => [
                'database' => __FILE__,
            ],
        ];

        $this->expectException(InvalidDatabaseException::class);

        $geoip = new GeoIP($config);
    }

    public function test_maxmind_web_api_exception()
    {
        $config = [
            'driver' => 'maxmind',
            'maxmind' => [
                'user_id' => 'test',
            ],
        ];

        $this->expectException(InvalidCredentialsException::class);

        $geoip = new GeoIP($config);
    }

    public function test_maxmind_web_api_authentication_exception()
    {
        $config = [
            'driver' => 'maxmind',
            'maxmind' => [
                'user_id' => 'test',
                'license_key' => 'test',
            ],
        ];

        $this->expectException(GeoIPException::class);

        $geoip = new GeoIP($config);
        $geoip = $geoip->setIp($this->validIp);

        $geoip->get();
    }

    public function test_maxmind_web_api_authentication_exception_getRaw()
    {
        $config = [
            'driver' => 'maxmind',
            'maxmind' => [
                'user_id' => 'test',
                'license_key' => 'test',
            ],
        ];

        $this->expectException(GeoIPException::class);

        $geoip = new GeoIP($config);
        $geoip = $geoip->setIp($this->validIp);

        $geoip->getRaw();
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

    public function test_ipstack_exception_without_key()
    {
        $config = [
            'driver' => 'ipstack',
        ];

        $this->expectException(InvalidCredentialsException::class);

        $geoip = new GeoIP($config);
        $geoip = $geoip->setIp($this->validIp);

        $geoip->get();
    }

    public function test_maxmind_database()
    {
        $config = [
            'driver' => 'maxmind',
            'maxmind' => [
                'database' => __DIR__.'/data/GeoIP2-City-Test.mmdb',
            ],
        ];

        $geoip = new GeoIP($config);
        $geoip = $geoip->setIp($this->validIp);

        $this->assertEquals('United Kingdom', $geoip->getCountry());

        $this->assertInstanceOf('GeoIp2\Model\City', $geoip->getRaw());

        $geoip = $geoip->setIp($this->invalidIp);

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

    public function test_telize_exception_without_key()
    {
        $config = [
            'driver' => 'telize',
        ];

        $this->expectException(InvalidCredentialsException::class);

        $geoip = new GeoIP($config);
        $geoip = $geoip->setIp($this->validIp);

        $geoip->get();
    }
}
