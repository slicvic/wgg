<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Services\IpInfoIpGeolocator;

class IpInfoIpGeolocatorTest extends TestCase
{
    private $instance;

    public function setUp()
    {
        $this->instance = new IpInfoIpGeolocator();
    }

    public function tearDown()
    {
        $this->instance = null;
    }

    public function testIpToGeolocationCouldNotDetermineLocation()
    {
        $ips = [
            '127.0.0.1',
            'some bogus string',
            '',
            '1000',
            '600.700.800.900'
        ];

        foreach ($ips as $ip) {
            $this->assertNull($this->instance->ipToGeolocation($ip));
        }
    }

    public function testIpToGeolocationSuccess()
    {
        // Google primary DNS server
        $ip = '8.8.8.8';
        $result = $this->instance->ipToGeolocation($ip);
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('city', $result);
        $this->assertArrayHasKey('lat', $result);
        $this->assertArrayHasKey('lng', $result);
        $this->assertEquals('Mountain View, California', $result['city']);
        $this->assertEquals('37.3860', $result['lat']);
        $this->assertEquals('-122.0838', $result['lng']);

        // SmartViper primary DNS server
        $ip = '208.76.50.50';
        $result = $this->instance->ipToGeolocation($ip);
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('city', $result);
        $this->assertArrayHasKey('lat', $result);
        $this->assertArrayHasKey('lng', $result);
        $this->assertEquals('Clearwater Beach, Florida', $result['city']);
        $this->assertEquals('27.9772', $result['lat']);
        $this->assertEquals('-82.8279', $result['lng']);

        // Yandex.DNS primary DNS server
        $ip = '77.88.8.8';
        $result = $this->instance->ipToGeolocation($ip);
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('city', $result);
        $this->assertArrayHasKey('lat', $result);
        $this->assertArrayHasKey('lng', $result);
        $this->assertEquals('Saint Petersburg, St.-Petersburg', $result['city']);
        $this->assertEquals('59.8944', $result['lat']);
        $this->assertEquals('30.2642', $result['lng']);
    }
}
