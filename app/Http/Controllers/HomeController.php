<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Facebook\Facebook;
use App\Contracts\GeoIpServiceInterface;
use App\Models\Event;

class HomeController extends BaseController
{
    /**
     * @var GeoIpServiceInterface
     */
    protected $geoIpService;

    /**
     * Constructor.
     *
     * @param GeoIpServiceInterface $geoIpService
     */
    public function __construct(GeoIpServiceInterface $geoIpService)
    {
        $this->geoIpService = $geoIpService;
    }

    /**
     * Show the home page.
     *
     * @param  Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        echo '<pre>';
        print_r($_SERVER);EXIT;
        $ip = $request->ip();
        $ip = '73.85.49.134';
        $geolocation = $this->geoIpService->getGeolocationByIp($ip);
        $input = [
            'keyword' => '',
            'distance' => 25,
            'lat' => $geolocation['lat'],
            'lng' => $geolocation['lng'],
            'city' => $geolocation['city']
        ];

        $events = Event::filterNearby($input['lat'], $input['lng'], $input['distance'])
            ->filterActive()
            ->filterUpcoming()
            ->limit(5)
            ->orderBySoonest()
            ->get();

        return view('home.index', compact('events', 'input'));
    }
}
