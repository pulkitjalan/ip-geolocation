<?php

namespace PulkitJalan\GeoIP\tests;

use Mockery;
use PHPUnit_Framework_TestCase;

class GeoIPUpdaterTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function testNoUpdate()
    {
        $geoipUpdater = new \PulkitJalan\GeoIP\GeoIPUpdater([]);

        $this->assertFalse($geoipUpdater->update());
    }

    public function testMaxmindUpdater()
    {
        $database = __DIR__.'/data/GeoLite2-City.mmdb';
        $config = [
            'driver'  => 'maxmind',
            'maxmind' => [
                'database' => $database,
            ],
        ];

        $geoipUpdater = new \PulkitJalan\GeoIP\GeoIPUpdater($config);

        $this->assertEquals($geoipUpdater->update(), $database);

        unlink($database);
    }
}
