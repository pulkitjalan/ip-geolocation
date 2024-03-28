<?php

use PulkitJalan\GeoIP\GeoIP;
use PulkitJalan\GeoIP\Exceptions\InvalidDriverException;

test('invalid driver exception', function () {
    $this->expectException(InvalidDriverException::class);

    $geoip = new GeoIP([]);
});

test('bad method call exception', function () {
    $this->expectException(BadMethodCallException::class);

    $geoip = new GeoIP();

    $geoip->setNothing();
});
