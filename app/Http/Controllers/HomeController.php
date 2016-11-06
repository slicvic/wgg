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

        $events = Event::nearby($geolocation['lat'], $geolocation['lng'], 10)
            //->fullTextSearch('')
            ->active()
            ->upcoming()
            ->get();


//        ->orderBy('start_at', 'ASC')
//        ->where('status_id', EventStatus::ACTIVE)
//        ->whereDate('start_at', '>=', date('Y-m-d'))
    //    ->whereDate('events.start_at', '>=', date('Y-m-d'))

    //    ->get();

        return view('home.index', compact('geolocation', 'events'));
    }
}
