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
        $clientIp = '73.85.49.134';//$request->ip();
        $clientGeo = $this->geoIpService->getGeolocationByIp($clientIp);

        $criteria = [
            'near' => [
                'lat' => $clientGeo['loc'][0],
                'lng' => $clientGeo['loc'][1],
                'within_miles' => 100
            ]
        ];

        $events = Event::searchQuery($criteria)->get();

        return view('home.index', compact('clientGeo', 'events'));
    }
}
