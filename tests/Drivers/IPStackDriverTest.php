<?php

namespace PulkitJalan\GeoIP\Tests\Drivers;

use PulkitJalan\GeoIP\GeoIP;
use PulkitJalan\GeoIP\Tests\AbstractTestCase;
use PulkitJalan\GeoIP\Exceptions\InvalidCredentialsException;

class IPStackDriverTest extends AbstractTestCase
{
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
}
