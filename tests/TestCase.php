<?php

namespace PulkitJalan\GeoIP\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected $multipleIps = '81.2.69.160,127.0.0.1';
    protected $validIp = '81.2.69.160';
    protected $invalidIp = '127.0.0.1';
}
