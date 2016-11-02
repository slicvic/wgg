<?php

namespace App\Services;

use App\Contracts\GeoIpServiceInterface;

/**
 * A client for ipinfo.io IP lookup API.
 *
 * @author Victor Lantigua
 * @link https://ipinfo.io/developers Developer documentation
 */
class IpInfoService implements GeoIpServiceInterface
{
    /**
     * The API URL.
     */
    const API_URL = 'http://ipinfo.io';

    /**
     * Get the geolocation information for the given IP.
     *
     * @param  string $ip
     * @return array
     */
    public function getGeolocationByIp($ip)
    {
        $result = [
            'city' => '',
            'region' => '',
            'country' => '',
            'loc' => ['', '']
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::API_URL . '/' . $ip . '/json');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $body = curl_exec($ch);
        $headers = curl_getinfo($ch);
        curl_close($ch);
        $decodedBody = json_decode($body, true);

        if (isset($decodedBody['loc'], $decodedBody['city'], $decodedBody['region'], $decodedBody['country'])) {
            $result['city'] = $decodedBody['city'];
            $result['region'] = $decodedBody['region'];
            $result['country'] = $decodedBody['country'];
            $result['loc'] = explode(',', $decodedBody['loc']);
        }

        return $result;
    }
}
