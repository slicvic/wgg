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
        $ip = '73.85.49.134';//$request->ip();
        $geolocation = $this->geoIpService->getGeolocationByIp($ip);

        $criteria = [
            'near' => [
                'lat' => $geolocation['lat'],
                'lng' => $geolocation['lng'],
                'within_miles' => 50
            ]
        ];

        $events = Event::createSearchQuery($criteria)
                        ->limit(10)
                        ->get();

        return view('home.index', compact('geolocation', 'events'));
    }
}
