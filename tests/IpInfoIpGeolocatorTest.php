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

    public function ipToGeolocationDataProvider()
    {
        return [
            // Google primary DNS server
            [
                '8.8.8.8', [
                    'city' => 'Mountain View, California',
                    'lat' => '37.3860',
                    'lng' => '-122.0838'
                ]
            ],
            // SmartViper primary DNS server
            [
                '208.76.50.50',
                [
                    'city' => 'Clearwater Beach, Florida',
                    'lat' => '27.9772',
                    'lng' => '-82.8279'
                ]
            ],
            // Yandex.DNS primary DNS server
            [
                '77.88.8.8',
                [
                    'city' => 'Saint Petersburg, St.-Petersburg',
                    'lat' => '59.8944',
                    'lng' => '30.2642'
                ]
            ]
        ];
    }

    public function testIpToGeolocationDidNotFindLocation()
    {
        $ips = [
            '127.0.0.1',
            'some bogus string',
            '',
            '1000',
            '600.700.800.900'
        ];

        foreach ($ips as $ip) {
            $this->assertSame(null, $this->instance->ipToGeolocation($ip));
        }
    }

    /**
     * @dataProvider ipToGeolocationDataProvider
     */
    public function testIpToGeolocationSuccess($ip, $expected)
    {
        $result = $this->instance->ipToGeolocation($ip);
        $this->assertInternalType('array', $expected);
        $this->assertArrayHasKey('city', $expected);
        $this->assertArrayHasKey('lat', $expected);
        $this->assertArrayHasKey('lng', $expected);
        $this->assertEquals($expected['city'], $result['city']);
        $this->assertEquals($expected['lat'], $result['lat']);
        $this->assertEquals($expected['lng'], $result['lng']);
    }
}
