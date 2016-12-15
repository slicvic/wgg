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
            'Test 1 location not found' => ['127.0.0.1', null],
            'Test 2 location not found' => ['some bogus string', null],
            'Test 3 location not found' => ['', null],
            'Test 4 location not found' => ['1000', null],
            'Test 5 location not found' => ['600.700.800.900', null],
            'Test 6 location found'     => [gethostbyname('google.com'), [
                    'city' => 'Mountain View, California',
                    'lat' => '37.4192',
                    'lng' => '-122.0574'
                ]],
            'Test 7 location found'     => [gethostbyname('yahoo.com'), [
                    'city' => 'Sunnyvale, California',
                    'lat' => '37.4249',
                    'lng' => '-122.0074'
                ]],
            'Test 8 location found'     => [gethostbyname('bing.com'), [
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

        $this->assertSame(gettype($expected), gettype($result));

        if (is_array($expected)) {
            $this->assertInternalType('array', $result);
            $this->assertArrayHasKey('city', $result);
            $this->assertArrayHasKey('lat', $result);
            $this->assertArrayHasKey('lng', $result);
            $this->assertSame($expected['city'], $result['city']);
            $this->assertSame($expected['lat'], $result['lat']);
            $this->assertSame($expected['lng'], $result['lng']);
        }
    }
}
