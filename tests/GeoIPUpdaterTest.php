<?php

namespace PulkitJalan\GeoIP\Tests;

use Mockery;
use PHPUnit_Framework_TestCase;
use PulkitJalan\GeoIP\GeoIPUpdater;

class GeoIPUpdaterTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function test_no_update()
    {
        $geoipUpdater = new GeoIPUpdater([]);

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

        $geoipUpdater = new GeoIPUpdater($config);

        $this->assertEquals($geoipUpdater->update(), $database);

        unlink($database);
    }

    public function test_maxmind_updater_invalid_url()
    {
        $database = __DIR__.'/data/GeoLite2-City.mmdb';
        $config = [
            'driver'  => 'maxmind',
            'maxmind' => [
                'database' => $database,
                'download' => 'http://example.com/maxmind_database.mmdb.gz'
            ],
        ];

        $geoipUpdater = new GeoIPUpdater($config);

        $this->assertFalse($geoipUpdater->update());
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

        $geoipUpdater = new GeoIPUpdater($config);

        $this->assertEquals($geoipUpdater->update(), $database);

        unlink($database);
        rmdir(pathinfo($database, PATHINFO_DIRNAME));
    }
}
