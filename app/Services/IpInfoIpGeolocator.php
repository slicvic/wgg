<?php

namespace App\Services;

use App\Contracts\IpGeolocatorInterface;

/**
 * This service class is a client for ipinfo.io IP lookup API.
 *
 * @author Victor Lantigua
 * @link https://ipinfo.io/developers For developer documentation
 */
class IpInfoIpGeolocator implements IpGeolocatorInterface
{
    /**
     * The API URL.
     */
    const API_URL = 'http://ipinfo.io';

    /**
     * {@inheritdoc}
     */
    public function ipToGeolocation(string $ip)
    {
        $geolocation = [
            'city' => null,
            'lat' => null,
            'lng' => null
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::API_URL . '/' . $ip . '/json');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $body = curl_exec($ch);
        $headers = curl_getinfo($ch);
        curl_close($ch);
        $decodedBody = json_decode($body, true);

        if (!(empty($decodedBody['loc']) && empty($decodedBody['city']) && empty($decodedBody['region']) && empty($decodedBody['country']))) {
            list($geolocation['lat'], $geolocation['lng']) = explode(',', $decodedBody['loc']);
            $geolocation['city'] = $decodedBody['city'] . ', ';
            $geolocation['city'] .= ('US' === $decodedBody['country']) ? $decodedBody['region'] : $decodedBody['country'];
        }

        return $geolocation;
    }
}
