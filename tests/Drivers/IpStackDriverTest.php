<?php

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PulkitJalan\GeoIP\Exceptions\GeoIPException;
use PulkitJalan\GeoIP\Exceptions\InvalidCredentialsException;
use PulkitJalan\GeoIP\GeoIP;

test('ipstack', function () {
    $config = [
        'driver' => 'ipstack',
        'ipstack' => [
            'key' => 'test',
        ],
    ];

    $client = Mockery::mock(Client::class);

    $client->shouldReceive('get')
        ->times(1)
        ->andReturn(
            new Response(
                200,
                [],
                json_encode([
                    'city' => 'test city',
                    'country_name' => 'test country name',
                    'country_code' => 'test country code',
                    'latitude' => 1,
                    'longitude' => -1,
                    'region_name' => 'test region name',
                    'region_code' => 'test region code',
                    'time_zone.id' => 'test timezone.id',
                    'zip' => 'test zip',
                ])
            )
        );

    $geoip = new GeoIP($config, $client);
    $geoip = $geoip->setIp($this->validIp);

    expect($geoip->get())->toEqual(
        [
            'city' => 'test city',
            'country' => 'test country name',
            'countryCode' => 'test country code',
            'latitude' => 1.0,
            'longitude' => -1.0,
            'region' => 'test region name',
            'regionCode' => 'test region code',
            'timezone' => 'test timezone.id',
            'postalCode' => 'test zip',
        ]
    );
});

test('ipstack throws exception getraw', function () {
    $config = [
        'driver' => 'ipstack',
        'ipstack' => [
            'key' => 'test',
        ],
    ];
    $this->expectException(GeoIPException::class);

    $geoip = new GeoIP($config);
    $geoip = $geoip->setIp($this->validIp);

    $geoip->getRaw();
});

test('ipstack throws exception without key', function () {
    $config = [
        'driver' => 'ipstack',
    ];

    $this->expectException(InvalidCredentialsException::class);

    $geoip = new GeoIP($config);
    $geoip = $geoip->setIp($this->validIp);

    $geoip->get();
});

test('ipstack secure config value defaults to true when missing', function () {
    $config = [
        'driver' => 'ipstack',
        'ipstack' => [
            'key' => 'test',
        ],
    ];

    $client = Mockery::mock(Client::class);

    $client->shouldReceive('get')
        ->withArgs(function ($url) {
            $this->expect($url)->toStartWith('https://');

            return true;
        })
        ->times(1)
        ->andReturn(
            new Response(200, [], json_encode(['city' => 'test city']))
        );

    $geoip = new GeoIP($config, $client);
    $geoip = $geoip->setIp($this->validIp);

    $geoip->get();
});
test('ipstack respects false secure config value', function () {
    $config = [
        'driver' => 'ipstack',
        'ipstack' => [
            'key' => 'test',
            'secure' => false,
        ],
    ];

    $client = Mockery::mock(Client::class);

    $client->shouldReceive('get')
        ->withArgs(function ($url) {
            $this->expect($url)->toStartWith('http://');

            return true;
        })
        ->times(1)
        ->andReturn(
            new Response(200, [], json_encode(['city' => 'test city']))
        );

    $geoip = new GeoIP($config, $client);
    $geoip = $geoip->setIp($this->validIp);

    $geoip->get();
});
test("ipstack respects true secure config value", function () {
    $config = [
        'driver' => 'ipstack',
        'ipstack' => [
            'key' => 'test',
            'secure' => true,
        ],
    ];

    $client = Mockery::mock(Client::class);

    $client->shouldReceive('get')
        ->withArgs(function ($url) {
            $this->expect($url)->toStartWith('https://');

            return true;
        })
        ->times(1)
        ->andReturn(
            new Response(200, [], json_encode(['city' => 'test city']))
        );

    $geoip = new GeoIP($config, $client);
    $geoip = $geoip->setIp($this->validIp);

    $geoip->get();
});