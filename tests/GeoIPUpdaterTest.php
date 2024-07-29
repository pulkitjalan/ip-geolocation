<?php

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Client as GuzzleClient;
use PulkitJalan\IPGeoLocation\IpGeolocationUpdater;
use PulkitJalan\IPGeoLocation\Exceptions\InvalidDatabaseException;
use PulkitJalan\IPGeoLocation\Exceptions\InvalidCredentialsException;

test('no database', function () {
    $this->expectException(InvalidDatabaseException::class);

    (new IpGeolocationUpdater([]))->update();
});

test('no license key', function () {
    $this->expectException(InvalidCredentialsException::class);

    $database = __DIR__.'/data/GeoLite2-City.mmdb';
    $config = [
        'driver' => 'maxmind_database',
        'maxmind_database' => [
            'database' => $database,
        ],
    ];

    (new IpGeolocationUpdater($config))->update();
});

test('maxmind updater', function () {
    $database = __DIR__.'/data/GeoLite2-City.mmdb';
    $config = [
        'driver' => 'maxmind_database',
        'maxmind_database' => [
            'database' => $database,
            'license_key' => 'test',
        ],
    ];

    // create the file
    $p = new PharData(__DIR__.'/data/test.tar');
    $p->addEmptyDir('GeoLite2-City_today');
    $p['GeoLite2-City_today/GeoLite2-City.mmdb'] = 'test';
    $p->compress(Phar::GZ);
    unlink(__DIR__.'/data/test.tar');
    rename(__DIR__.'/data/test.tar.gz', __DIR__.'/data/ipGeolocation.tar.gz');

    $client = Mockery::mock(GuzzleClient::class);

    $client->shouldReceive('get')
        ->once()
        ->withSomeOfArgs('https://download.maxmind.com/app/geoip_download?edition_id=GeoLite2-City&suffix=tar.gz&license_key=test')
        ->andReturn(new Response);

    $geoipUpdater = new IpGeolocationUpdater($config, $client);

    expect($database)->toEqual($geoipUpdater->update());

    @unlink($database);
    @unlink(__DIR__.'/data/ipGeolocation.tar.gz');
});

test('maxmind updater invalid url', function () {
    $database = __DIR__.'/data/GeoLite2-City.mmdb';
    $config = [
        'driver' => 'maxmind_database',
        'maxmind_database' => [
            'database' => $database,
            'download' => 'http://example.com/maxmind_database.mmdb.gz?license_key=',
            'license_key' => 'test',
        ],
    ];

    $client = Mockery::mock(GuzzleClient::class);

    $client->shouldReceive('get')
        ->once()
        ->withSomeOfArgs('http://example.com/maxmind_database.mmdb.gz?license_key=test')
        ->andThrow(new Exception);

    $geoipUpdater = new IpGeolocationUpdater($config, $client);

    expect($geoipUpdater->update())->toBeFalse();
});
