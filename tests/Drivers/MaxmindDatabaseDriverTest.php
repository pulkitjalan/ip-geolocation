<?php

namespace PulkitJalan\GeoIP\Tests\Drivers;

use PulkitJalan\GeoIP\GeoIP;
use PulkitJalan\GeoIP\Tests\AbstractTestCase;
use PulkitJalan\GeoIP\Exceptions\InvalidDatabaseException;
use PulkitJalan\GeoIP\Exceptions\InvalidCredentialsException;

class MaxmindDatabaseDriverTest extends AbstractTestCase
{
    public function test_maxmind_database()
    {
        $config = [
            'driver' => 'maxmind_database',
            'maxmind_database' => [
                'database' => __DIR__.'/../data/GeoIP2-City-Test.mmdb',
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

    public function test_maxmind_database_config_exception()
    {
        $this->expectException(InvalidCredentialsException::class);

        $geoip = new GeoIP(['driver' => 'maxmind_database']);
    }

    public function test_maxmind_database_exception()
    {
        $config = [
            'driver' => 'maxmind_database',
            'maxmind_database' => [
                'database' => __DIR__.'/data/GeoIP2-City.mmdb',
            ],
        ];

        $this->expectException(InvalidCredentialsException::class);

        $geoip = new GeoIP($config);
    }

    public function test_maxmind_invalid_database_exception()
    {
        $config = [
            'driver' => 'maxmind_database',
            'maxmind_database' => [
                'database' => __FILE__,
            ],
        ];

        $this->expectException(InvalidDatabaseException::class);

        $geoip = new GeoIP($config);
    }
}
