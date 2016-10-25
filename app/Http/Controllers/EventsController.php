<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests\StoreEventFormRequest;
use App\Models\Event;
use App\Services\EventService;

class EventsController extends BaseController
{
    /**
     * @var EventService
     */
    protected $eventService;

    /**
     * Constructor.
     *
     * @param EventService $eventService
     */
    public function __construct(EventService $eventService)
    {
        $this->middleware('auth')->only([
            'create',
            'store',
            'edit',
            'update'
        ]);

        $this->eventService = $eventService;
    }

    /**
     * Show form to create a new event.
     *
     * @param  Request $request
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        return view('events.create', ['event' => new Event]);
    }

    /**
     * Create a new event.
     *
     * @param  StoreEventFormRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreEventFormRequest $request)
    {
        $input = $request->only([
            'event.title',
            'event.type_id',
            'event.status_id',
            'event.start_at',
            'event.duration',
            'event.description',
            'venue.name',
            'venue.latlng',
            'venue.address',
            'venue.url'
        ]);

        $this->eventService->create($input);

        return response()->json();
    }

    /**
     * Show form to edit a given event.
     *
     * @param  Request $request
     * @param int $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request, $id)
    {
        $event = Event::find($id);

        if (!$event) {
            return $this->redirectWithError('home', 'Event not found.');
        }

        if ($event->user_id !== Auth::user()->id) {
            return $this->redirectWithError('home', 'Unauthorized.');
        }

        return view('events.edit', ['event' => $event]);
    }

    /**
     * Update a given event.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(StoreEventFormRequest $request, $id)
    {
        $event = Event::find($id);

        if (!$event) {
            return $this->redirectWithError('home', 'Event not found.');
        }

        if ($event->user_id !== Auth::user()->id) {
            return $this->redirectWithError('home', 'Unauthorized.');
        }

        $input = $request->only([
            'event.title',
            'event.type_id',
            'event.status_id',
            'event.start_at',
            'event.duration',
            'event.description',
            'venue.name',
            'venue.latlng',
            'venue.address',
            'venue.url'
        ]);

        $this->eventService->update($event, $input);

        return response()->json();
    }
}
