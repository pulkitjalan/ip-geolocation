<?php

namespace PulkitJalan\GeoIP\Tests\Drivers;

use Mockery;
use PulkitJalan\GeoIP\GeoIP;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Client as GuzzleClient;
use PulkitJalan\GeoIP\Tests\AbstractTestCase;
use PulkitJalan\GeoIP\Exceptions\GeoIPException;
use PulkitJalan\GeoIP\Exceptions\InvalidCredentialsException;

class IPStackDriverTest extends AbstractTestCase
{
    public function test_ipstack()
    {
        $config = [
            'driver' => 'ipstack',
            'ipstack' => [
                'key' => 'test',
            ],
        ];

        $client = Mockery::mock(GuzzleClient::class);

        $client->shouldReceive('get')
            ->once()
            ->andReturn(new Response(200, [], json_encode(['city' => 'test'])));

        $geoip = new GeoIP($config, $client);
        $geoip = $geoip->setIp($this->validIp);

        $this->assertEquals('test', $geoip->get('city'));
    }

    public function test_ipstack_exception_getRaw()
    {
        $config = [
            'driver' => 'ipstack',
            'ipstack' => [
                'key' => 'test',
            ],
        ];

        $this->expectException(GeoIPException::class);

        $geoip = new GeoIP($config);
        $geoip = $geoip->setIp($this->validIp);

        $geoip->getRaw();
    }

    public function test_ipstack_exception_without_key()
    {
        $config = [
            'driver' => 'ipstack',
        ];

        $this->expectException(InvalidCredentialsException::class);

        $geoip = new GeoIP($config);
        $geoip = $geoip->setIp($this->validIp);

        $geoip->get();
    }
}
