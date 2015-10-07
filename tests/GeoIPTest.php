<?php

namespace PulkitJalan\GeoIP\Tests;

use Mockery;
use PHPUnit_Framework_TestCase;
use PulkitJalan\GeoIP\Exceptions\GeoIPException;
use PulkitJalan\GeoIP\Exceptions\InvalidCredentialsException;
use PulkitJalan\GeoIP\Exceptions\InvalidDatabaseException;
use PulkitJalan\GeoIP\Exceptions\InvalidDriverException;
use PulkitJalan\GeoIP\GeoIP;
use BadMethodCallException;

class GeoIPTest extends PHPUnit_Framework_TestCase
{
    protected $multipleIps = '81.2.69.160,127.0.0.1';
    protected $validIp = '81.2.69.160';
    protected $invalidIp = '127.0.0.1';

    public function tearDown()
    {
        Mockery::close();
    }

    public function test_invalid_driver_exception()
    {
        $this->setExpectedException(InvalidDriverException::class);

        $geoip = new GeoIP([]);
    }

    public function test_bad_method_call_exception()
    {
        $this->setExpectedException(BadMethodCallException::class);

        $geoip = new GeoIP();

        $geoip->setNothing();
    }

    public function test_maxmind_exception()
    {
        $this->setExpectedException(InvalidCredentialsException::class);

        $geoip = new GeoIP(['driver' => 'maxmind']);
    }

    public function test_maxmind_database_exception()
    {
        $config = [
            'driver'  => 'maxmind',
            'maxmind' => [
                'database' => __DIR__.'/data/GeoIP2-City.mmdb',
            ],
        ];

        $this->setExpectedException(InvalidCredentialsException::class);

        $geoip = new GeoIP($config);
    }

    public function test_maxmind_invalid_database_exception()
    {
        $config = [
            'driver'  => 'maxmind',
            'maxmind' => [
                'database' => __FILE__,
            ],
        ];

        $this->setExpectedException(InvalidDatabaseException::class);

        $geoip = new GeoIP($config);
    }

    public function test_maxmind_web_api_exception()
    {
        $config = [
            'driver'  => 'maxmind',
            'maxmind' => [
                'user_id' => 'test',
            ],
        ];

        $this->setExpectedException(InvalidCredentialsException::class);

        $geoip = new GeoIP($config);
    }

    public function test_maxmind_web_api_authentication_exception()
    {
        $config = [
            'driver'  => 'maxmind',
            'maxmind' => [
                'user_id'     => 'test',
                'license_key' => 'test',
            ],
        ];

        $this->setExpectedException(GeoIPException::class);

        $geoip = new GeoIP($config);
        $geoip = $geoip->setIp($this->validIp);

        $geoip->get();
    }

    public function test_get_random_ipaddress()
    {
        $config = [
            'driver' => 'ip-api',
            'random' => true,
        ];

        $geoip = new GeoIP($config);
        $ip = $geoip->getIp();

        $this->assertNotEquals($ip, $this->invalidIp);
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

        $this->assertEquals($ip, $this->invalidIp);
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

        $this->assertEquals($ip, $this->validIp);
        $this->assertTrue(! (filter_var($ip, FILTER_VALIDATE_IP)) === false);
    }

    public function test_maxmind_database()
    {
        $config = [
            'driver'  => 'maxmind',
            'maxmind' => [
                'database' => __DIR__.'/data/GeoIP2-City-Test.mmdb',
            ],
        ];

        $geoip = new GeoIP($config);
        $geoip = $geoip->setIp($this->validIp);

        $this->assertEquals($geoip->getCountry(), 'United Kingdom');

        $geoip = $geoip->setIp($this->invalidIp);

        $this->assertEquals($geoip->get(), []);
        $this->assertEquals($geoip->getCountry(), '');
    }

    public function test_ip_api_pro_exception()
    {
        $config = [
            'driver' => 'ip-api',
            'ip-api' => [
                'key'    => 'test',
                'secure' => true,
            ],
        ];

        $this->setExpectedException(GeoIPException::class);

        $geoip = new GeoIP($config);
        $geoip = $geoip->setIp($this->validIp);

        $geoip->get();
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

        $this->assertEquals($geoip->get(), []);
        $this->assertEquals($geoip->getCountry(), '');
    }

    public function test_telize()
    {
        $config = [
            'driver' => 'telize',
        ];

        $geoip = new GeoIP($config);
        $geoip = $geoip->setIp($this->validIp);

        $this->assertEquals($geoip->getCountry(), 'United Kingdom');

        $geoip = $geoip->setIp($this->invalidIp);

        $this->assertEquals($geoip->get(), []);
        $this->assertEquals($geoip->getCountry(), '');
    }

    public function test_telize_secure()
    {
        $config = [
            'driver' => 'telize',
            'telize' => [
                'secure' => true,
            ],
        ];

        $geoip = new GeoIP($config);
        $geoip = $geoip->setIp($this->validIp);

        $this->assertEquals($geoip->getCountry(), 'United Kingdom');

        $geoip = $geoip->setIp($this->invalidIp);

        $this->assertEquals($geoip->get(), []);
        $this->assertEquals($geoip->getCountry(), '');
    }
}
