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

    public function test_no_update()
    {
        $geoipUpdater = new \PulkitJalan\GeoIP\GeoIPUpdater([]);

        $this->assertFalse($geoipUpdater->update());
    }

    public function test_maxmind_updater()
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

    public function test_maxmind_updater_dir_not_exist()
    {
        $database = __DIR__.'/data/new_dir/GeoLite2-City.mmdb';
        $config = [
            'driver'  => 'maxmind',
            'maxmind' => [
                'database' => $database,
            ],
        ];

        $geoipUpdater = new \PulkitJalan\GeoIP\GeoIPUpdater($config);

        $this->assertEquals($geoipUpdater->update(), $database);

        unlink($database);
        rmdir(pathinfo($database, PATHINFO_DIRNAME));
    }
}
