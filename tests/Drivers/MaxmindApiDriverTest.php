<?php

use PulkitJalan\GeoIP\GeoIP;
use PulkitJalan\GeoIP\Exceptions\GeoIPException;
use PulkitJalan\GeoIP\Exceptions\InvalidCredentialsException;

test('maxmind api config exception', function () {
    $this->expectException(InvalidCredentialsException::class);

    $geoip = new GeoIP(['driver' => 'maxmind_api']);
});

test('maxmind web api exception', function () {
    $config = [
        'driver' => 'maxmind_api',
        'maxmind_api' => [
            'user_id' => 1234,
        ],
    ];

    $this->expectException(InvalidCredentialsException::class);

    $geoip = new GeoIP($config);
});

test('maxmind web api authentication exception', function () {
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
});

test('maxmind web api authentication exception get raw', function () {
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
});
