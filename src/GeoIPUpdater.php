<?php

namespace PulkitJalan\GeoIP;

use GuzzleHttp\Client as GuzzleClient;
use PulkitJalan\Requester\Requester;
use Exception;

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

        if (!file_exists($dir = pathinfo($database, PATHINFO_DIRNAME))) {
            mkdir($dir, 0777, true);
        }

        $file = $this->requester->url($maxmindDatabaseUrl)->get()->getBody();

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
        do {
            $tempName = uniqid('temp ');
        } while (file_exists($tempName));

        if (file_put_contents($tempName, $data)) {
            try {
                ob_start();
                @readgzfile($tempName);
                $uncompressed = ob_get_clean();
            } catch (Exception $e) {
                $ex = $e;
            }

            unlink($tempName);

            if (isset($ex)) {
                throw $ex;
            }

            return $uncompressed;
        }
    }
}
