<?php

namespace PulkitJalan\GeoIP;

use Exception;
use Illuminate\Support\Arr;
use GuzzleHttp\Client as GuzzleClient;
use PulkitJalan\GeoIP\Exceptions\InvalidDatabaseException;
use PulkitJalan\GeoIP\Exceptions\InvalidCredentialsException;

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

        $this->guzzle = $guzzle ?? new GuzzleClient();
    }

    /**
     * Main update function.
     *
     * @return string|false
     */
    public function update()
    {
        if (! Arr::get($this->config, 'maxmind_database.database', false)) {
            throw new InvalidDatabaseException();
        }

        if (! Arr::get($this->config, 'maxmind_database.license_key', false)) {
            throw new InvalidCredentialsException();
        }

        return $this->updateMaxmindDatabase();
    }

    /**
     * Update function for maxmind database.
     *
     * @return string
     */
    protected function updateMaxmindDatabase()
    {
        $maxmindDatabaseUrl = Arr::get($this->config, 'maxmind_database.download', 'https://download.maxmind.com/app/geoip_download?edition_id=GeoLite2-City&suffix=tar.gz&license_key=');

        $maxmindDatabaseUrl = $maxmindDatabaseUrl.Arr::get($this->config, 'maxmind_database.license_key');

        $database = Arr::get($this->config, 'maxmind_database.database', false);

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
