<?php

namespace PulkitJalan\GeoIP;

use Exception;
use Illuminate\Support\Arr;
use GuzzleHttp\Client as GuzzleClient;

class GeoIPUpdater
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var \GuzzleHttp\Client
     */
    protected $guzzle;

    /**
     * @param array $config
     */
    public function __construct(array $config, GuzzleClient $guzzle = null)
    {
        $this->config = $config;

        $this->guzzle = $guzzle ?: new GuzzleClient();
    }

    /**
     * Main update function.
     *
     * @return string|false
     */
    public function update()
    {
        if (Arr::get($this->config, 'maxmind.database', false)) {
            return $this->updateMaxmindDatabase();
        }

        return false;
    }

    /**
     * Update function for maxmind database.
     *
     * @return string
     */
    protected function updateMaxmindDatabase()
    {
        $maxmindDatabaseUrl = Arr::get($this->config, 'maxmind.download', 'http://geolite.maxmind.com/download/geoip/database/GeoLite2-City.mmdb.gz');

        $database = Arr::get($this->config, 'maxmind.database', false);

        if (! file_exists($dir = pathinfo($database, PATHINFO_DIRNAME))) {
            mkdir($dir, 0777, true);
        }

        try {
            $file = $this->guzzle->get($maxmindDatabaseUrl)->getBody();

            file_put_contents($database, gzdecode($file));
        } catch (Exception $e) {
            return false;
        }

        return $database;
    }
}
