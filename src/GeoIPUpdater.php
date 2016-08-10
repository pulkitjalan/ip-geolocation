<?php

namespace PulkitJalan\GeoIP;

use GuzzleHttp\Client as GuzzleClient;
use Exception;

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
     * Update function for maxmind database.
     *
     * @return string
     */
    protected function updateMaxmindDatabase()
    {
        $maxmindDatabaseUrl = 'http://geolite.maxmind.com/download/geoip/database/GeoLite2-City.mmdb.gz';

        $database = array_get($this->config, 'maxmind.database', false);

        if (! file_exists($dir = pathinfo($database, PATHINFO_DIRNAME))) {
            mkdir($dir, 0777, true);
        }

        $file = $this->guzzle->get($maxmindDatabaseUrl)->getBody();

        try {
            file_put_contents($database, $this->gzdecode($file));
        } catch (Exception $e) {
            return false;
        }

        return $database;
    }

    /**
     * gzdecode function.
     * 
     * @param mixed $data
     * @return mixed
     */
    protected function gzdecode($data)
    {
        if (!function_exists('gzdecode')) {
            return gzinflate(substr($data, 10, -8));
        }

        return gzdecode($data);
    }
}
