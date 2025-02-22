<?php

use PulkitJalan\IPGeolocation\IPGeolocation;
use PulkitJalan\IPGeolocation\Exceptions\InvalidDriverException;

test('invalid driver exception', function () {
    $this->expectException(InvalidDriverException::class);

    $ip = new IPGeolocation([]);
});

test('bad method call exception', function () {
    $this->expectException(BadMethodCallException::class);

    $ip = new IPGeolocation;

    $ip->setNothing();
});
