<?php

use GuzzleHttp\Client;
use Mockery\MockInterface;
use PulkitJalan\IPGeolocation\IPGeolocation;
use PulkitJalan\IPGeolocation\Exceptions\InvalidCredentialsException;

test('ip2location throws exception without api key', function () {
    $config = [
        'driver' => 'ip2location',
    ];

    $this->expectException(InvalidCredentialsException::class);

    $ip = new IPGeolocation($config);
    $ip = $ip->setIp('127.0.0.1');

    $ip->get();
});

test('ip2location returns correct data', function () {
    $config = [
        'driver' => 'ip2location',
        'ip2location' => [
            'api_key' => 'test_key',
        ],
    ];

    /** @var MockInterface|Client $client */
    $client = Mockery::mock(Client::class);

    $client->shouldReceive('get')
        ->times(1)
        ->andReturn(
            new \GuzzleHttp\Psr7\Response(
                200,
                [],
                json_encode([
                    'response' => 'OK',
                    'country_code' => 'US',
                    'country_name' => 'United States of America',
                    'region_name' => 'California',
                    'region_code' => 'CA',
                    'city_name' => 'Los Angeles',
                    'latitude' => 34.05223,
                    'longitude' => -118.24368,
                    'zip_code' => '90001',
                    'time_zone' => '-07:00',
                ])
            )
        );

    $ip = new IPGeolocation($config, $client);
    $ip = $ip->setIp('127.0.0.1');

    expect($ip->get())->toEqual([
        'city' => 'Los Angeles',
        'country' => 'United States of America',
        'countryCode' => 'US',
        'latitude' => 34.05223,
        'longitude' => -118.24368,
        'region' => 'California',
        'regionCode' => 'CA',
        'timezone' => '-07:00',
        'postalCode' => '90001',
    ]);
});

test('ip2location returns default when response is invalid', function () {
    $config = [
        'driver' => 'ip2location',
        'ip2location' => [
            'api_key' => 'test_key',
        ],
    ];

    /** @var MockInterface|Client $client */
    $client = Mockery::mock(Client::class);

    $client->shouldReceive('get')
        ->times(1)
        ->andReturn(
            new \GuzzleHttp\Psr7\Response(
                200,
                [],
                json_encode(['response' => 'FAILED'])
            )
        );

    $ip = new IPGeolocation($config, $client);
    $ip = $ip->setIp('127.0.0.1');

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
});

test('ip2location returns raw data', function () {
    $mockResponse = [
        'response' => 'OK',
        'country_code' => 'US',
        'country_name' => 'United States of America',
        'region_name' => 'California',
        'city_name' => 'Los Angeles',
    ];

    $config = [
        'driver' => 'ip2location',
        'ip2location' => [
            'api_key' => 'test_key',
        ],
    ];

    /** @var MockInterface|Client $client */
    $client = Mockery::mock(Client::class);

    $client->shouldReceive('get')
        ->times(1)
        ->andReturn(
            new \GuzzleHttp\Psr7\Response(
                200,
                [],
                json_encode($mockResponse)
            )
        );

    $ip = new IPGeolocation($config, $client);
    $ip = $ip->setIp('127.0.0.1');

    expect($ip->getRaw())->toBe($mockResponse);
});
