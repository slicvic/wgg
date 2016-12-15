<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Facebook\Facebook;
use App\Contracts\IpGeolocatorInterface;
use App\Models\Event;

class HomeController extends BaseController
{
    /**
     * @var IpGeolocatorInterface
     */
    protected $ipGeolocator;

    /**
     * Constructor.
     *
     * @param IpGeolocatorInterface $ipGeolocator
     */
    public function __construct(IpGeolocatorInterface $ipGeolocator)
    {
        $this->ipGeolocator = $ipGeolocator;
    }

    /**
     * Show the home page.
     *
     * @param  Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $ip = $request->ip();
        $ip = '73.85.49.134';
        
        $input = [
            'keyword' => '',
            'distance' => 25,
            'lat' => '',
            'lng' => '',
            'city' => ''
        ];

        if ($geolocation = $this->ipGeolocator->ipToGeolocation($ip)) {
            $input['city'] = $geolocation['city'];
            $input['lat'] = $geolocation['lat'];
            $input['lng'] = $geolocation['lng'];
        }

        $events = Event::filterNearby($input['lat'], $input['lng'], $input['distance'])
            ->filterActive()
            ->filterUpcoming()
            ->limit(5)
            ->orderBySoonest()
            ->get();

        return view('home.index', compact('events', 'input'));
    }
}
