<?php

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PulkitJalan\IPGeolocation\Drivers\IPQueryDriver;

beforeEach(function () {
    $this->config = ['key' => 'test-key'];
});

it('can get geolocation data from ipquery', function () {
    $mock = new MockHandler([
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
        ])),
    ]);

    $handler = HandlerStack::create($mock);
    $guzzle = new GuzzleClient(['handler' => $handler]);

    $driver = new IPQueryDriver($this->config, $guzzle);
    $data = $driver->get('8.8.8.8');

    expect($data)
        ->toHaveKey('city', 'Los Angeles')
        ->toHaveKey('country', 'United States')
        ->toHaveKey('countryCode', 'US')
        ->toHaveKey('latitude', 34.05220)
        ->toHaveKey('longitude', -118.24370)
        ->toHaveKey('region', 'California')
        ->toHaveKey('regionCode', null)
        ->toHaveKey('timezone', 'America/Los_Angeles')
        ->toHaveKey('postalCode', '90012');
});

it('returns default values when error occurs', function () {
    $mock = new MockHandler([
        new Response(200, [], json_encode(['error' => 'Invalid IP address'])),
    ]);

    $handler = HandlerStack::create($mock);
    $guzzle = new GuzzleClient(['handler' => $handler]);

    $driver = new IPQueryDriver($this->config, $guzzle);
    $data = $driver->get('invalid-ip');

    expect($data)
        ->toHaveKey('city', null)
        ->toHaveKey('country', null)
        ->toHaveKey('countryCode', null)
        ->toHaveKey('latitude', null)
        ->toHaveKey('longitude', null)
        ->toHaveKey('region', null)
        ->toHaveKey('regionCode', null)
        ->toHaveKey('timezone', null)
        ->toHaveKey('postalCode', null);
});

it('throws exception when api key is missing', function () {
    $driver = new IPQueryDriver([]);

    expect(fn () => $driver->get('8.8.8.8'))
        ->toThrow(InvalidArgumentException::class, 'IPQuery API key is required');
});