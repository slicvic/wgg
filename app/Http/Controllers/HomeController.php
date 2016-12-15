<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Facebook\Facebook;
use App\Contracts\LocationFinderInterface;
use App\Models\Event;

class HomeController extends BaseController
{
    /**
     * @var LocationFinderInterface
     */
    protected $locationFinder;

    /**
     * Constructor.
     *
     * @param LocationFinderInterface $locationFinder
     */
    public function __construct(LocationFinderInterface $locationFinder)
    {
        $this->locationFinder = $locationFinder;
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
        $geolocation = $this->locationFinder->ipToGeolocation($ip);
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
