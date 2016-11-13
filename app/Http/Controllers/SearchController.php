<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contracts\GeoIpServiceInterface;
use App\Models\Event;

class SearchController extends BaseController
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
     * Search events.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function events(Request $request)
    {
        $ip = $request->ip();
        $ip = '73.85.49.134';
        $geolocation = $this->geoIpService->getGeolocationByIp($ip);
        $perPage = 4;
        $defaultRadius = 25;
        $input = $request->only([
            'keywords',
            'radius',
            'lat',
            'lng',
            'city',
            'type'
        ]);

        $input['radius'] = $input['radius'] ?: $defaultRadius;

        if (!$input['city']) {
            $input['lat'] = $geolocation['lat'];
            $input['lng'] = $geolocation['lng'];
            $input['city'] = $geolocation['city'];
        }

        $events = Event::query()
            ->filterActive()
            ->filterUpcoming()
            ->orderBySoonest();

        if ($input['keywords']) {
            $events->filterKeywords($input['keywords']);
        }

        if ($input['radius']) {
            $events->filterNearby($input['lat'], $input['lng'], $input['radius']);
        }

        if ($input['type']) {
            $events->filterTypes($input['type']);
        }

        $events = $events->paginate($perPage);
        $events->appends($input);

        return view('search.events', compact('events', 'input'));
    }
}
