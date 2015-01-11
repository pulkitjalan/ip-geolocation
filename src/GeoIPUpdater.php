<?php

namespace PulkitJalan\GeoIP;

use GuzzleHttp\Client as GuzzleClient;
use PulkitJalan\Requester\Requester;

class GeoIPUpdater
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var \PulkitJalan\Requester\Requester
     */
    protected $requester;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;

        $this->requester = with(new Requester(new GuzzleClient()))->retry(2)->every(50);
    }

    /**
     * Main update function
     *
     * @return bool|string
     */
    public function update()
    {
        if (array_get($this->config, 'maxmind.database', false)) {
            return $this->updateMaxmindDatabase();
        }

        return false;
    }

    /**
     * Update function for maxmind database
     *
     * @return string
     */
    protected function updateMaxmindDatabase()
    {
        $maxmindDatabaseUrl = 'http://geolite.maxmind.com/download/geoip/database/GeoLite2-City.mmdb.gz';

        $database = array_get($this->config, 'maxmind.database', '/tmp/GeoLite2-City.mmdb');

        $file = $this->requester->url($maxmindDatabaseUrl)->get()->getBody();

        @file_put_contents($database, gzdecode($file));

        return $database;
    }
}
