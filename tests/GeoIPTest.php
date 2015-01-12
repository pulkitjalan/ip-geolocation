<?php

namespace PulkitJalan\GeoIP\Tests;

use PHPUnit_Framework_TestCase;
use Mockery;

class GeoIPTest extends PHPUnit_Framework_TestCase
{
    protected $validIP = '81.2.69.160';
    protected $invalidIP = '127.0.0.1';

    public function tearDown()
    {
        Mockery::close();
    }

    public function testInvalidDriverException()
    {
        $this->setExpectedException('PulkitJalan\GeoIP\Exceptions\InvalidDriverException');

        $geoip = new \PulkitJalan\GeoIP\GeoIP([]);
    }

    public function testBadMethodCallException()
    {
        $this->setExpectedException('BadMethodCallException');

        $geoip = new \PulkitJalan\GeoIP\GeoIP();

        $geoip->setNothing();
    }

    public function testMaxmindException()
    {
        $this->setExpectedException('PulkitJalan\GeoIP\Exceptions\InvalidCredentialsException');

        $geoip = new \PulkitJalan\GeoIP\GeoIP(['driver' => 'maxmind']);
    }

    public function testMaxmindDatabaseException()
    {
        $config = [
            'driver' => 'maxmind',
            'maxmind' => [
                'database' => __DIR__.'/data/GeoIP2-City.mmdb',
            ],
        ];

        $this->setExpectedException('PulkitJalan\GeoIP\Exceptions\InvalidCredentialsException');

        $geoip = new \PulkitJalan\GeoIP\GeoIP($config);
    }

    public function testMaxmindInvalidDatabaseException()
    {
        $config = [
            'driver' => 'maxmind',
            'maxmind' => [
                'database' => __FILE__,
            ],
        ];

        $this->setExpectedException('MaxMind\Db\Reader\InvalidDatabaseException');

        $geoip = new \PulkitJalan\GeoIP\GeoIP($config);
    }

    public function testMaxmindWebApiException()
    {
        $config = [
            'driver' => 'maxmind',
            'maxmind' => [
                'user_id' => 'test',
            ],
        ];

        $this->setExpectedException('PulkitJalan\GeoIP\Exceptions\InvalidCredentialsException');

        $geoip = new \PulkitJalan\GeoIP\GeoIP($config);
    }

    public function testMaxmindWebApiAuthenticationException()
    {
        $config = [
            'driver' => 'maxmind',
            'maxmind' => [
                'user_id' => 'test',
                'license_key' => 'test',
            ],
        ];

        $this->setExpectedException('PulkitJalan\GeoIP\Exceptions\GeoIPException');

        $geoip = new \PulkitJalan\GeoIP\GeoIP($config);
        $geoip = $geoip->setIP($this->validIP);

        $geoip->get();
    }

    public function testMaxmindDatabase()
    {
        $config = [
            'driver' => 'maxmind',
            'maxmind' => [
                'database' => __DIR__.'/data/GeoIP2-City-Test.mmdb',
            ],
        ];

        $geoip = new \PulkitJalan\GeoIP\GeoIP($config);
        $geoip = $geoip->setIP($this->validIP);

        $this->assertEquals($geoip->getCountry(), 'United Kingdom');

        $geoip = $geoip->setIP($this->invalidIP);

        $this->assertEquals($geoip->get(), []);
        $this->assertEquals($geoip->getCountry(), '');
    }

    public function testIpApiProException()
    {
        $config = [
            'driver' => 'ip-api',
            'ip-api' => [
                'key' => 'test',
            ],
        ];

        $this->setExpectedException('PulkitJalan\GeoIP\Exceptions\GeoIPException');

        $geoip = new \PulkitJalan\GeoIP\GeoIP($config);
        $geoip = $geoip->setIP($this->validIP);

        $this->assertEquals($geoip->getCountry(), '');
    }

    public function testIpApi()
    {
        $config = [
            'driver' => 'ip-api',
        ];

        $geoip = new \PulkitJalan\GeoIP\GeoIP($config);
        $geoip = $geoip->setIP($this->validIP);

        $this->assertEquals($geoip->getCountry(), 'United Kingdom');

        $geoip = $geoip->setIP($this->invalidIP);

        $this->assertEquals($geoip->get(), []);
        $this->assertEquals($geoip->getCountry(), '');
    }
}
