<?php

use PulkitJalan\IPGeolocation\IPGeolocation;
use PulkitJalan\IPGeolocation\Exceptions\InvalidDatabaseException;
use PulkitJalan\IPGeolocation\Exceptions\InvalidCredentialsException;

test('maxmind database', function () {
    $config = [
        'driver' => 'maxmind_database',
        'maxmind_database' => [
            'database' => __DIR__.'/../data/GeoIP2-City-Test.mmdb',
        ],
    ];

    $ip = new IPGeolocation($config);
    $ip = $ip->setIp($this->validIp);

    expect($ip->getCountry())->toEqual('United Kingdom');

    expect($ip->getRaw())->toBeInstanceOf('GeoIp2\Model\City');

    $ip = $ip->setIp($this->invalidIp);

    expect($ip->get())->toEqual([
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

    expect($ip->getCountry())->toEqual('');
});

test('maxmind database config exception', function () {
    $this->expectException(InvalidCredentialsException::class);

    $ip = new IPGeolocation(['driver' => 'maxmind_database']);
});

test('maxmind database exception', function () {
    $config = [
        'driver' => 'maxmind_database',
        'maxmind_database' => [
            'database' => __DIR__.'/data/GeoIP2-City.mmdb',
        ],
    ];

    $this->expectException(InvalidCredentialsException::class);

    $ip = new IPGeolocation($config);
});

test('maxmind invalid database exception', function () {
    $config = [
        'driver' => 'maxmind_database',
        'maxmind_database' => [
            'database' => __FILE__,
        ],
    ];

    $this->expectException(InvalidDatabaseException::class);

    $ip = new IPGeolocation($config);
});
