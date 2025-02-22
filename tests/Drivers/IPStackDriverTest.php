<?php

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PulkitJalan\IPGeolocation\IPGeolocation;
use PulkitJalan\IPGeolocation\Exceptions\IPGeolocationException;
use PulkitJalan\IPGeolocation\Exceptions\InvalidCredentialsException;

test('ipstack', function () {
    $config = [
        'driver' => 'ipstack',
        'ipstack' => [
            'key' => 'test',
        ],
    ];

    /** @var Mockery\MockInterface|Client $client */
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

    $ip = new IPGeolocation($config, $client);
    $ip = $ip->setIp($this->validIp);

    expect($ip->get())->toEqual(
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

test('ipstack should return default when response is empty', function () {
    $config = [
        'driver' => 'ipstack',
        'ipstack' => [
            'key' => 'test',
        ],
    ];

    /** @var Mockery\MockInterface|Client $client */
    $client = Mockery::mock(Client::class);

    $client->shouldReceive('get')
        ->times(1)
        ->andReturn(
            new Response(
                200,
                [],
                json_encode([])
            )
        );

    $ip = new IPGeolocation($config, $client);
    $ip = $ip->setIp($this->validIp);

    expect($ip->get())->toEqual(
        [
            'city' => null,
            'country' => null,
            'countryCode' => null,
            'latitude' => null,
            'longitude' => null,
            'region' => null,
            'regionCode' => null,
            'timezone' => null,
            'postalCode' => null,
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
    $this->expectException(IPGeolocationException::class);

    $ip = new IPGeolocation($config);
    $ip = $ip->setIp($this->validIp);

    $ip->getRaw();
});

test('ipstack throws exception without key', function () {
    $config = [
        'driver' => 'ipstack',
    ];

    $this->expectException(InvalidCredentialsException::class);

    $ip = new IPGeolocation($config);
    $ip = $ip->setIp($this->validIp);

    $ip->get();
});

test('ipstack secure config value defaults to true when missing', function () {
    $config = [
        'driver' => 'ipstack',
        'ipstack' => [
            'key' => 'test',
        ],
    ];

    /** @var Mockery\MockInterface|Client $client */
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

    $ip = new IPGeolocation($config, $client);
    $ip = $ip->setIp($this->validIp);

    $ip->get();
});

test('ipstack respects false secure config value', function () {
    $config = [
        'driver' => 'ipstack',
        'ipstack' => [
            'key' => 'test',
            'secure' => false,
        ],
    ];

    /** @var Mockery\MockInterface|Client $client */
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

    $ip = new IPGeolocation($config, $client);
    $ip = $ip->setIp($this->validIp);

    $ip->get();
});

test('ipstack respects true secure config value', function () {
    $config = [
        'driver' => 'ipstack',
        'ipstack' => [
            'key' => 'test',
            'secure' => true,
        ],
    ];

    /** @var Mockery\MockInterface|Client $client */
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

    $ip = new IPGeolocation($config, $client);
    $ip = $ip->setIp($this->validIp);

    $ip->get();
});
