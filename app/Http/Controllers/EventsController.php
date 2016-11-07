<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\StoreEventFormRequest;
use App\Contracts\EventServiceInterface;
use App\Models\Event;
use App\Models\EventStatus;

class EventsController extends BaseController
{
    /**
     * @var EventService
     */
    protected $eventService;

    /**
     * Constructor.
     *
     * @param EventServiceInterface $eventService
     */
    public function __construct(EventServiceInterface $eventService)
    {
        $this->middleware('auth')->except([
            'search',
            'show'
        ]);

        $this->eventService = $eventService;
    }

    /**
     * Show the form for creating a new event.
     *
     * @param  Request $request
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        $event = new Event;

        return view('events.create', compact('event'));
    }

    /**
     * Create a new event.
     *
     * @param  StoreEventFormRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postCreate(StoreEventFormRequest $request)
    {
        $input = $request->only([
            'event.title',
            'event.type_id',
            'event.start_at',
            'event.description',
            'venue.name',
            'venue.lat',
            'venue.lng',
            'venue.address',
            'venue.url'
        ]);

        $input['event']['user_id'] = Auth::user()->id;

        $event = $this->eventService->create($input);

        $this->flashSuccess(trans('messages.event.created', ['title' => $event->present()->title()]));

        return response()->json();
    }

    /**
     * Show the form for editing an existing event.
     *
     * @param  Request $request
     * @param  int $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        // Check if the user is allowed to edit this event
        $this->authorize('update', $event);

        return view('events.edit', compact('event'));
    }

    /**
     * Update a given event.
     *
     * @param  Request $request
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function postEdit(StoreEventFormRequest $request, $id)
    {
        $event = Event::findOrFail($id);

        // Check if the user is allowed to edit this event
        $this->authorize('update', $event);

        $input = $request->only([
            'event.title',
            'event.type_id',
            'event.start_at',
            'event.description',
            'venue.name',
            'venue.lat',
            'venue.lng',
            'venue.address',
            'venue.url'
        ]);

        $this->eventService->update($event, $input);

        $this->flashSuccess(trans('messages.event.updated', ['title' => $event->present()->title()]));

        return response()->json();
    }

    /**
     * Show the form for rescheduling an event.
     *
     * @param  Request $request
     * @param  int $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function reschedule(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        // Check if the user is allowed to reschedule this event
        $this->authorize('reschedule', $event);

        return view('events.create', compact('event'));
    }

    /**
     * Reschedule a given event.
     *
     * @param  Request $request
     * @param  int $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function postReschedule(StoreEventFormRequest $request, $id)
    {
        $event = Event::findOrFail($id);

        // Check if the user is allowed to reschedule this event
        $this->authorize('reschedule', $event);

        $input = $request->only([
            'event.title',
            'event.type_id',
            'event.start_at',
            'event.description',
            'venue.name',
            'venue.lat',
            'venue.lng',
            'venue.address',
            'venue.url'
        ]);

        $this->eventService->reschedule($event, $input);

        $this->flashSuccess(trans('messages.event.created', ['title' => $event->present()->title()]));

        return response()->json();
    }

    /**
     * Cancel a given event.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        // Check if the user is allowed to cancel this event
        $this->authorize('update', $event);

        // Cancel the event
        $event->cancel();

        return $this->redirectBackWithSuccess(trans('messages.event.canceled', ['title' => $event->present()->title()]));
    }

    /**
     * Show a given event.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        return view('events.show', compact('event'));
    }
}
