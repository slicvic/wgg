<?php

namespace App\Contracts;

interface IpGeolocatorInterface
{
    /**
     * Get the geolocation information for a given IP.
     *
     * @param  string $ip
     * @return array In the form of:
     *    [
     *      'city' => 'Miami, Florida',
     *      'lat' => '129',
     *      'lng' => '123'
     *    ]
     */
    public function ipToGeolocation(string $ip);
}
