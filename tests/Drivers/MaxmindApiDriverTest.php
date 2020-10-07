<?php

namespace PulkitJalan\GeoIP\Tests\Drivers;

use PulkitJalan\GeoIP\GeoIP;
use PulkitJalan\GeoIP\Tests\AbstractTestCase;
use PulkitJalan\GeoIP\Exceptions\GeoIPException;
use PulkitJalan\GeoIP\Exceptions\InvalidCredentialsException;

class MaxmindApiDriverTest extends AbstractTestCase
{
    public function test_maxmind_api_config_exception()
    {
        $this->expectException(InvalidCredentialsException::class);

        $geoip = new GeoIP(['driver' => 'maxmind_api']);
    }

    public function test_maxmind_web_api_exception()
    {
        $config = [
            'driver' => 'maxmind_api',
            'maxmind_api' => [
                'user_id' => 1234,
            ],
        ];

        $this->expectException(InvalidCredentialsException::class);

        $geoip = new GeoIP($config);
    }

    public function test_maxmind_web_api_authentication_exception()
    {
        $config = [
            'driver' => 'maxmind_api',
            'maxmind_api' => [
                'user_id' => 1234,
                'license_key' => 'test',
            ],
        ];

        // expect the exception since the credentials are invalid.
        $this->expectException(GeoIPException::class);

        $geoip = new GeoIP($config);
        $geoip = $geoip->setIp($this->validIp);

        $geoip->get();
    }

    public function test_maxmind_web_api_authentication_exception_getRaw()
    {
        $config = [
            'driver' => 'maxmind_api',
            'maxmind_api' => [
                'user_id' => 1234,
                'license_key' => 'test',
            ],
        ];

        // expect the exception since the credentials are invalid.
        $this->expectException(GeoIPException::class);

        $geoip = new GeoIP($config);
        $geoip = $geoip->setIp($this->validIp);

        $geoip->getRaw();
    }
}
