<?php

namespace PulkitJalan\GeoIP\Tests\Drivers;

use PulkitJalan\GeoIP\GeoIP;
use PulkitJalan\GeoIP\Tests\AbstractTestCase;
use PulkitJalan\GeoIP\Exceptions\InvalidCredentialsException;

class TelizeDriverTest extends AbstractTestCase
{
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
