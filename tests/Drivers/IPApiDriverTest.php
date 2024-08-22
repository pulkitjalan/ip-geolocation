<?php

use Illuminate\Support\Arr;
use PulkitJalan\IPGeoLocation\IPGeoLocation;
use PulkitJalan\IPGeoLocation\Exceptions\IPGeolocationException;

test('ip api', function () {
    $config = [
        'driver' => 'ip-api',
    ];

    $ip = new IPGeoLocation($config);
    $ip = $ip->setIp($this->validIp);

    expect('United Kingdom')->toEqual($ip->getCountry());

    $ip = $ip->setIp($this->invalidIp);

    expect(Arr::get($ip->getRaw(), 'status'))->toEqual('fail');

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

test('get multiple ipaddress', function () {
    $config = [
        'driver' => 'ip-api',
    ];

    $ip = new IPGeoLocation($config);
    $ip->setIp($this->multipleIps);
    $ip = $ip->getIp();

    expect($ip)->toEqual($this->validIp);
    expect(! filter_var($ip, FILTER_VALIDATE_IP) === false)->toBeTrue();
});

test('get random ipaddress', function () {
    $config = [
        'driver' => 'ip-api',
        'random' => true,
    ];

    $ip = new IPGeoLocation($config);
    $ip = $ip->getIp();

    $this->assertNotEquals($this->invalidIp, $ip);
    expect(! filter_var($ip, FILTER_VALIDATE_IP) === false)->toBeTrue();
});

test('get non random ipaddress', function () {
    $config = [
        'driver' => 'ip-api',
        'random' => false,
    ];

    $ip = new IPGeoLocation($config);
    $ip = $ip->getIp();

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

    $this->expectException(IPGeolocationException::class);

    $ip = new IPGeoLocation($config);
    $ip = $ip->setIp($this->validIp);

    $ip->get();
});

test('ip api pro exception get raw', function () {
    $config = [
        'driver' => 'ip-api',
        'ip-api' => [
            'key' => 'test',
        ],
    ];

    $this->expectException(IPGeolocationException::class);

    $ip = new IPGeoLocation($config);
    $ip = $ip->setIp($this->validIp);

    $ip->getRaw();
});
