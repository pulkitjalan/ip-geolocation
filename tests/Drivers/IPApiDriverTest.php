<?php

use Illuminate\Support\Arr;
use PulkitJalan\GeoIP\GeoIP;
use PulkitJalan\GeoIP\Exceptions\GeoIPException;

test('ip api', function () {
    $config = [
        'driver' => 'ip-api',
    ];

    $geoip = new GeoIP($config);
    $geoip = $geoip->setIp($this->validIp);

    expect('United Kingdom')->toEqual($geoip->getCountry());

    $geoip = $geoip->setIp($this->invalidIp);

    expect(Arr::get($geoip->getRaw(), 'status'))->toEqual('fail');

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

test('get multiple ipaddress', function () {
    $config = [
        'driver' => 'ip-api',
    ];

    $geoip = new GeoIP($config);
    $geoip->setIp($this->multipleIps);
    $ip = $geoip->getIp();

    expect($ip)->toEqual($this->validIp);
    expect(! filter_var($ip, FILTER_VALIDATE_IP) === false)->toBeTrue();
});

test('get random ipaddress', function () {
    $config = [
        'driver' => 'ip-api',
        'random' => true,
    ];

    $geoip = new GeoIP($config);
    $ip = $geoip->getIp();

    $this->assertNotEquals($this->invalidIp, $ip);
    expect(! filter_var($ip, FILTER_VALIDATE_IP) === false)->toBeTrue();
});

test('get non random ipaddress', function () {
    $config = [
        'driver' => 'ip-api',
        'random' => false,
    ];

    $geoip = new GeoIP($config);
    $ip = $geoip->getIp();

    expect($ip)->toEqual($this->invalidIp);
    expect(! filter_var($ip, FILTER_VALIDATE_IP) === false)->toBeTrue();
});

test('ip api pro exception', function () {
    $config = [
        'driver' => 'ip-api',
        'ip-api' => [
            'key' => 'test',
        ],
    ];

    $this->expectException(GeoIPException::class);

    $geoip = new GeoIP($config);
    $geoip = $geoip->setIp($this->validIp);

    $geoip->get();
});

test('ip api pro exception get raw', function () {
    $config = [
        'driver' => 'ip-api',
        'ip-api' => [
            'key' => 'test',
        ],
    ];

    $this->expectException(GeoIPException::class);

    $geoip = new GeoIP($config);
    $geoip = $geoip->setIp($this->validIp);

    $geoip->getRaw();
});
