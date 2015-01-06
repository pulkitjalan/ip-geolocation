<?php

namespace PulkitJalan\GeoIP;

class GeoIP
{
    /**
     * @var string
     */
    protected $ip;

    /**
     * Set ip
     *
     * @return PulkitJalan\GeoIP\GeoIP
     */
    public function setIP($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip from server info
     *
     * @return string ipaddress
     */
    public function getIP()
    {
        return ($this->ip) ?: array_get($_SERVER, 'HTTP_CLIENT_IP', array_get($_SERVER, 'HTTP_X_FORWARDED_FOR', array_get($_SERVER, 'HTTP_X_FORWARDED', array_get($_SERVER, 'HTTP_FORWARDED_FOR', array_get($_SERVER, 'HTTP_FORWARDED', array_get($_SERVER, 'REMOTE_ADDR', '127.0.0.1'))))));
    }
}
