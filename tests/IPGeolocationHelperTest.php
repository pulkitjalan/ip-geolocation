<?php

use PulkitJalan\IPGeolocation\IPGeolocation;

global $ipGeolocationHelperFake;

$ipGeolocationHelperFake = new class
{
    public function getIpAddress()
    {
        return '127.0.0.1';
    }
};

if (! function_exists('app')) {
    function app($abstract = null)
    {
        global $ipGeolocationHelperFake;

        return $ipGeolocationHelperFake;
    }
}

test('helper resolves snake case keys through getter methods', function () use ($ipGeolocationHelperFake) {
    expect(ipGeolocation())->toBe($ipGeolocationHelperFake)
        ->and(ipGeolocation('ip_address'))->toBe('127.0.0.1');
});
