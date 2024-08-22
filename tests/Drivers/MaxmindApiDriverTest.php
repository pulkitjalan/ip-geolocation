<?php

use PulkitJalan\IPGeoLocation\IPGeoLocation;
use PulkitJalan\IPGeoLocation\Exceptions\IPGeolocationException;
use PulkitJalan\IPGeoLocation\Exceptions\InvalidCredentialsException;

test('maxmind api config exception', function () {
    $this->expectException(InvalidCredentialsException::class);

    $ip = new IPGeoLocation(['driver' => 'maxmind_api']);
});

test('maxmind web api exception', function () {
    $config = [
        'driver' => 'maxmind_api',
        'maxmind_api' => [
            'user_id' => 1234,
        ],
    ];

    $this->expectException(InvalidCredentialsException::class);

    $ip = new IPGeoLocation($config);
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
    $this->expectException(IPGeolocationException::class);

    $ip = new IPGeoLocation($config);
    $ip = $ip->setIp($this->validIp);

    $ip->get();
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
    $this->expectException(IPGeolocationException::class);

    $ip = new IPGeoLocation($config);
    $ip = $ip->setIp($this->validIp);

    $ip->getRaw();
});
