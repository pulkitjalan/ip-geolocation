<?php

use GuzzleHttp\Client;
use Mockery\MockInterface;
use PulkitJalan\IPGeolocation\IPGeolocation;
use PulkitJalan\IPGeolocation\Exceptions\InvalidCredentialsException;

test('ipinfo throws exception without token', function () {
    $config = [
        'driver' => 'ipinfo',
    ];

    $this->expectException(InvalidCredentialsException::class);

    $ip = new IPGeolocation($config);
    $ip = $ip->setIp('8.8.8.8');

    $ip->get();
});

test('ipinfo returns correct data', function () {
    $config = [
        'driver' => 'ipinfo',
        'ipinfo' => [
            'token' => 'test_token',
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
                    'ip' => '8.8.8.8',
                    'city' => 'Mountain View',
                    'region' => 'California',
                    'country' => 'US',
                    'loc' => '37.4056,-122.0775',
                    'postal' => '94043',
                    'timezone' => 'America/Los_Angeles',
                ])
            )
        );

    $ip = new IPGeolocation($config, $client);
    $ip = $ip->setIp('8.8.8.8');

    expect($ip->get())->toEqual([
        'city' => 'Mountain View',
        'country' => 'US',
        'countryCode' => 'US',
        'latitude' => 37.4056,
        'longitude' => -122.0775,
        'region' => 'California',
        'regionCode' => 'California',
        'timezone' => 'America/Los_Angeles',
        'postalCode' => '94043',
    ]);
});

test('ipinfo returns default when response is invalid', function () {
    $config = [
        'driver' => 'ipinfo',
        'ipinfo' => [
            'token' => 'test_token',
        ],
    ];

    /** @var MockInterface|Client $client */
    $client = Mockery::mock(Client::class);

    $client->shouldReceive('get')
        ->times(1)
        ->andReturn(
            new \GuzzleHttp\Psr7\Response(400, [])
        );

    $ip = new IPGeolocation($config, $client);
    $ip = $ip->setIp('invalid_ip');

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

test('ipinfo returns raw data', function () {
    $mockResponse = [
        'ip' => '8.8.8.8',
        'city' => 'Mountain View',
        'region' => 'California',
        'country' => 'US',
        'loc' => '37.4056,-122.0775',
        'postal' => '94043',
        'timezone' => 'America/Los_Angeles',
    ];

    $config = [
        'driver' => 'ipinfo',
        'ipinfo' => [
            'token' => 'test_token',
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
    $ip = $ip->setIp('8.8.8.8');

    expect($ip->getRaw())->toBe($mockResponse);
});
