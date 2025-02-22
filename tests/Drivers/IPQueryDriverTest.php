<?php

use GuzzleHttp\Client;
use Mockery\MockInterface;
use GuzzleHttp\Psr7\Response;
use PulkitJalan\IPGeolocation\IPGeolocation;
use PulkitJalan\IPGeolocation\Exceptions\IPGeolocationException;

test('ipquery driver', function () {
    $config = [
        'driver' => 'ipquery',
        'ipquery' => [
            'key' => 'test-key',
        ],
    ];

    /** @var MockInterface|Client $client */
    $client = Mockery::mock(Client::class);

    $client->shouldReceive('get')
        ->once()
        ->andReturn(
            new Response(200, [], json_encode([
                'location' => [
                    'city' => 'Los Angeles',
                    'country' => 'United States',
                    'country_code' => 'US',
                    'latitude' => 34.0522,
                    'longitude' => -118.2437,
                    'state' => 'California',
                    'timezone' => 'America/Los_Angeles',
                    'zipcode' => '90012',
                ],
            ]))
        );

    $ip = new IPGeolocation($config, $client);
    $ip = $ip->setIp('8.8.8.8');

    expect($ip->get())
        ->toHaveKey('city', 'Los Angeles')
        ->toHaveKey('country', 'United States')
        ->toHaveKey('countryCode', 'US')
        ->toHaveKey('latitude', 34.05220)
        ->toHaveKey('longitude', -118.24370)
        ->toHaveKey('region', 'California')
        ->toHaveKey('regionCode', null)
        ->toHaveKey('timezone', 'America/Los_Angeles')
        ->toHaveKey('postalCode', '90012');

    expect('Los Angeles')->toEqual($ip->getCity());
    expect('United States')->toEqual($ip->getCountry());
});

test('ipquery driver returns default values when error occurs', function () {
    $config = [
        'driver' => 'ipquery',
        'ipquery' => [
            'key' => 'test-key',
        ],
    ];

    /** @var MockInterface|Client $client */
    $client = Mockery::mock(Client::class);

    $client->shouldReceive('get')
        ->once()
        ->andReturn(
            new Response(200, [], json_encode(['error' => 'Invalid IP address']))
        );

    $ip = new IPGeolocation($config, $client);
    $ip = $ip->setIp('invalid-ip');

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

    expect($ip->getCity())->toEqual('');
    expect($ip->getCountry())->toEqual('');
});

test('ipquery driver throws exception when api key is missing', function () {
    $config = [
        'driver' => 'ipquery',
    ];

    $ip = new IPGeolocation($config);
    $ip = $ip->setIp('8.8.8.8');

    expect(fn () => $ip->get())
        ->toThrow(IPGeolocationException::class, 'Failed to get ip geolocation data');
});
