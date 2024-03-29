<?php

use PulkitJalan\GeoIP\GeoIP;
use PulkitJalan\GeoIP\Exceptions\InvalidDatabaseException;
use PulkitJalan\GeoIP\Exceptions\InvalidCredentialsException;

test('maxmind database', function () {
    $config = [
        'driver' => 'maxmind_database',
        'maxmind_database' => [
            'database' => __DIR__.'/../data/GeoIP2-City-Test.mmdb',
        ],
    ];

    $geoip = new GeoIP($config);
    $geoip = $geoip->setIp($this->validIp);

    expect($geoip->getCountry())->toEqual('United Kingdom');

    expect($geoip->getRaw())->toBeInstanceOf('GeoIp2\Model\City');

    $geoip = $geoip->setIp($this->invalidIp);

    expect($geoip->get())->toEqual([
        'city' => null,
        'country' => null,
        'countryCode' => null,
        'latitude' => null,
        'longitude' => null,
        'region' => null,
        'regionCode' => null,
        'timezone' => null,
        'postalCode' => null,
    ]);

    expect($geoip->getCountry())->toEqual('');
});

test('maxmind database config exception', function () {
    $this->expectException(InvalidCredentialsException::class);

    $geoip = new GeoIP(['driver' => 'maxmind_database']);
});

test('maxmind database exception', function () {
    $config = [
        'driver' => 'maxmind_database',
        'maxmind_database' => [
            'database' => __DIR__.'/data/GeoIP2-City.mmdb',
        ],
    ];

    $this->expectException(InvalidCredentialsException::class);

    $geoip = new GeoIP($config);
});

test('maxmind invalid database exception', function () {
    $config = [
        'driver' => 'maxmind_database',
        'maxmind_database' => [
            'database' => __FILE__,
        ],
    ];

    $this->expectException(InvalidDatabaseException::class);

    $geoip = new GeoIP($config);
});
