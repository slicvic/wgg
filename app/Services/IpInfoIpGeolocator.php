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
        if (empty($ip)) {
            return null;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::API_URL . '/' . $ip . '/json');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $body = curl_exec($ch);
        $headers = curl_getinfo($ch);
        curl_close($ch);
        $decodedBody = json_decode($body, true);

        if (
            empty($decodedBody['loc'])
            || empty($decodedBody['city'])
            || empty($decodedBody['region'])
        ) {
            return null;
        }

        $result = [
            'city' => sprintf('%s, %s', $decodedBody['city'], $decodedBody['region']),
            'lat' => '',
            'lng' => ''

        ];

        list($result['lat'], $result['lng']) = explode(',', $decodedBody['loc']);

        return $result;
    }
}
