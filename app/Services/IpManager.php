<?php

namespace PanteraFox\Services;

use PanteraFox\Country;

class IpManager
{
    /**
     * @return string
     */
    public function getIP() {
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
                        return $ip;
                    }
                }
            }
        }
    }

    /**
     * @return array
     */
    public function getIpLocation()
    {
        return $this->getIpLocationViaAPI($this->getIP());
    }

    /**
     * @param string $ipAddress
     * @return array
     */
    private function getIpLocationViaAPI($ipAddress)
    {
        return json_decode(file_get_contents('http://geoip.nekudo.com/api/'. $ipAddress), true);
    }

    public function getAllCountries()
    {
        return Country::all();
    }
}