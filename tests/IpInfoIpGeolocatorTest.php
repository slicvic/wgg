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
            ['127.0.0.1', null],
            ['some bogus string', null],
            ['', null],
            ['1000', null],
            ['600.700.800.900', null],
            [gethostbyname('google.com'), [
                    'city' => 'Mountain View, California',
                    'lat' => '37.4192',
                    'lng' => '-122.0574'
                ]],
            [gethostbyname('yahoo.com'), [
                    'city' => 'Sunnyvale, California',
                    'lat' => '37.4249',
                    'lng' => '-122.0074'
                ]],
            [gethostbyname('bing.com'), [
                    'city' => 'Redmond, Washington',
                    'lat' => '47.6801',
                    'lng' => '-122.1206'
                ]]
        ];
    }

    /**
     * @dataProvider ipToGeolocationDataProvider
     */
    public function testIpToGeolocation($ip, $expected)
    {
        $result = $this->instance->ipToGeolocation($ip);

        $this->assertSame($expected, $result);
    }
}
