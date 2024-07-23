<?php

use PulkitJalan\IPGeoLocation\IPGeoLocation;
use PulkitJalan\IPGeoLocation\Exceptions\InvalidDriverException;

test('invalid driver exception', function () {
    $this->expectException(InvalidDriverException::class);

    $ip = new IPGeoLocation([]);
});

test('bad method call exception', function () {
    $this->expectException(BadMethodCallException::class);

    $ip = new IPGeoLocation();

    $ip->setNothing();
});
