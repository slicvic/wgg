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
     * Get the geolocation information for a given IP.
     *
     * @param  string $ip
     * @return array
     */
    public function getGeolocationByIp($ip)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::API_URL . '/' . $ip . '/json');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $body = curl_exec($ch);
        $headers = curl_getinfo($ch);
        curl_close($ch);
        $decodedBody = json_decode($body, true);

        $result = [
            'city' => null,
            'lat' => null,
            'lng' => null
        ];

        if (!(empty($decodedBody['loc']) && empty($decodedBody['city']) && empty($decodedBody['region']) && empty($decodedBody['country']))) {
            list($result['lat'], $result['lng']) = explode(',', $decodedBody['loc']);
            $result['city'] = ('US' === $decodedBody['country'])
                            ? $decodedBody['city'] . ', ' . $decodedBody['region']
                            : $decodedBody['city'] . ', ' . $decodedBody['country'];
        }

        return $result;
    }
}
