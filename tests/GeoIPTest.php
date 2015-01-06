<?php

namespace PulkitJalan\GeoIP\Tests;

use PHPUnit_Framework_TestCase;
use Mockery;

class GeoIPTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $database = __DIR__.'/data/GeoIP2-City-Test.mmdb';
        $this->geoip = Mockery::mock('PulkitJalan\GeoIP\GeoIP', [$database])->makePartial();
        $this->geoip->setIP('81.2.69.160');
    }

    public function tearDown()
    {
        Mockery::close();
    }
}
