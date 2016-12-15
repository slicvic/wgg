<?php

namespace App\Contracts;

interface IpGeolocatorInterface
{
    /**
     * Get the geolocation information for a specific IP address.
     *
     * @param  string $ip
     * @return null|array In the form of:
     *    [
     *      'city' => 'Miami, Florida',
     *      'lat' => '129',
     *      'lng' => '123'
     *    ]
     */
    public function ipToGeolocation(string $ip);
}
