<?php

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use PulkitJalan\IPGeolocation\Drivers\IP2LocationDriver;
use PulkitJalan\IPGeolocation\Exceptions\InvalidCredentialsException;

test('it throws exception with invalid credentials', function () {
    expect(fn () => new IP2LocationDriver([]))
        ->toThrow(InvalidCredentialsException::class);
});

test('it returns default for invalid response', function () {
    $mock = new MockHandler([
        new Response(200, [], json_encode(['response' => 'FAILED'])),
    ]);

    $handler = HandlerStack::create($mock);
    $client = new Client(['handler' => $handler]);

    $driver = new IP2LocationDriver(['api_key' => 'test_key'], $client);

    expect($driver->get('127.0.0.1'))->toEqual([
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

test('it returns correct data', function () {
    $mockResponse = [
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
    ];

    $mock = new MockHandler([
        new Response(200, [], json_encode($mockResponse)),
    ]);

    $handler = HandlerStack::create($mock);
    $client = new Client(['handler' => $handler]);

    $driver = new IP2LocationDriver(['api_key' => 'test_key'], $client);

    $result = $driver->get('127.0.0.1');

    $expected = [
        'city' => 'Los Angeles',
        'country' => 'United States of America',
        'countryCode' => 'US',
        'latitude' => 34.05223,
        'longitude' => -118.24368,
        'region' => 'California',
        'regionCode' => 'CA',
        'timezone' => '-07:00',
        'postalCode' => '90001',
    ];

    expect($result)->toBe($expected);
});

test('it handles raw data', function () {
    $mockResponse = [
        'response' => 'OK',
        'country_code' => 'US',
        'country_name' => 'United States of America',
        'region_name' => 'California',
        'city_name' => 'Los Angeles',
    ];

    $mock = new MockHandler([
        new Response(200, [], json_encode($mockResponse)),
    ]);

    $handler = HandlerStack::create($mock);
    $client = new Client(['handler' => $handler]);

    $driver = new IP2LocationDriver(['api_key' => 'test_key'], $client);

    $result = $driver->getRaw('127.0.0.1');

    expect($result)->toBe($mockResponse);
});
