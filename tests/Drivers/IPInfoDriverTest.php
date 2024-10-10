<?php

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PulkitJalan\IPGeolocation\Drivers\IPInfoDriver;
use PulkitJalan\IPGeolocation\Exceptions\InvalidCredentialsException;

beforeEach(function () {
    $this->validConfig = ['token' => 'test_token'];
    $this->invalidConfig = [];
});

it('throws exception with invalid config', function () {
    expect(fn () => new IPInfoDriver($this->invalidConfig))
        ->toThrow(InvalidCredentialsException::class);
});

it('returns correct data for valid IP', function () {
    $mockResponse = [
        'ip' => '8.8.8.8',
        'city' => 'Mountain View',
        'region' => 'California',
        'country' => 'US',
        'loc' => '37.4056,-122.0775',
        'postal' => '94043',
        'timezone' => 'America/Los_Angeles',
    ];

    $mock = new MockHandler([
        new Response(200, [], json_encode($mockResponse)),
    ]);

    $handlerStack = HandlerStack::create($mock);
    $client = new Client(['handler' => $handlerStack]);

    $driver = new IPInfoDriver($this->validConfig, $client);
    $result = $driver->get('8.8.8.8');

    expect($result)->toMatchArray([
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

it('returns default data for invalid response', function () {
    $mock = new MockHandler([
        new Response(400, []),
    ]);

    $handlerStack = HandlerStack::create($mock);
    $client = new Client(['handler' => $handlerStack]);

    $driver = new IPInfoDriver($this->validConfig, $client);

    expect($driver->get('invalid_ip'))->toEqual([
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

it('returns raw data for valid IP', function () {
    $mockResponse = [
        'ip' => '8.8.8.8',
        'city' => 'Mountain View',
        'region' => 'California',
        'country' => 'US',
        'loc' => '37.4056,-122.0775',
        'postal' => '94043',
        'timezone' => 'America/Los_Angeles',
    ];

    $mock = new MockHandler([
        new Response(200, [], json_encode($mockResponse)),
    ]);

    $handlerStack = HandlerStack::create($mock);
    $client = new Client(['handler' => $handlerStack]);

    $driver = new IPInfoDriver($this->validConfig, $client);
    $result = $driver->getRaw('8.8.8.8');

    expect($result)->toBe($mockResponse);
});