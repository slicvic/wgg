<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contracts\IpGeolocatorInterface;
use App\Models\Event;

class SearchController extends BaseController
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
     * Search events.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function events(Request $request)
    {
        $ip = $request->ip();
        $ip = '73.85.49.134';
        $geolocation = $this->ipGeolocator->ipToGeolocation($ip);
        $perPage = 4;
        $defaultDistance = 25;
        $input = $request->only([
            'keyword',
            'distance',
            'lat',
            'lng',
            'city',
            'type'
        ]);

        $input['distance'] = $input['distance'] ?: $defaultDistance;

        if (!$input['city']) {
            $input['lat'] = $geolocation['lat'];
            $input['lng'] = $geolocation['lng'];
            $input['city'] = $geolocation['city'];
        }

        $events = Event::query()
            ->filterActive()
            ->filterUpcoming()
            ->orderBySoonest();

        if ($input['keyword']) {
            $events->filterKeyword($input['keyword']);
        }

        if ($input['distance'] && is_numeric($input['distance'])) {
            $events->filterNearby($input['lat'], $input['lng'], $input['distance']);
        }

        if ($input['type']) {
            $events->filterTypes($input['type']);
        }

        $events = $events->paginate($perPage);
        $events->appends($input);

        return view('search.events.result', compact('events', 'input'));
    }
}
