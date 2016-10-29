<?php
namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use App\Http\Requests\StoreEventFormRequest;
use App\Services\EventService;
use App\Models\Event;
use App\Models\EventStatus;

class EventsController extends BaseController
{
    /**
     * Instance of event service.
     *
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
        $this->middleware('auth')->except([
            'search'
        ]);

        $this->eventService = $eventService;
    }

    /**
     * Show the form for creating a new event.
     *
     * @param  Request $request
     * @return \Illuminate\View\View
     */
    public function getCreate(Request $request)
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
        $input['event']['status_id'] = EventStatus::ACTIVE;

        try {
            $event = $this->eventService->create($input);
            $this->flashSuccess(trans('messages.event.created', ['title' => $event->present()->title()]));
            return response()->json();
        } catch (Exception $e) {
            return response()->json(['error' => trans('messages.system.something_went_wrong')], 500);
        }
    }

    /**
     * Show the form for editing an existing event.
     *
     * @param  Request $request
     * @param  int $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function getEdit(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        // Check if the user is allowed to edit this event
        $this->authorize('update', $event);

        return view('events.edit', compact('event'));
    }

    /**
     * Update the given event.
     *
     * @param  Request $request
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function postEdit(StoreEventFormRequest $request, $id)
    {
        try {
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
        } catch (NotFoundHttpException $e) {
            return response()->json(['error' => trans('messages.event.not_found')], 404);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        } catch (Exception $e) {
            return response()->json(['error' => trans('messages.system.something_went_wrong')], 500);
        }
    }

    /**
     * Show the form for rescheduling an event.
     *
     * @param  Request $request
     * @param  int $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function getReschedule(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        // Check if the user is allowed to reschedule this event
        $this->authorize('reschedule', $event);

        return view('events.create', compact('event'));
    }

    /**
     * Reschedule the given event.
     *
     * @param  Request $request
     * @param  int $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function postReschedule(StoreEventFormRequest $request, $id)
    {
        try {
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
        } catch (NotFoundHttpException $e) {
            return response()->json(['error' => trans('messages.event.not_found')], 404);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        } catch (Exception $e) {
            return response()->json(['error' => trans('messages.system.something_went_wrong')], 500);
        }
    }

    /**
     * Cancel the given event.
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
     * Search events.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        Event::search();
    }
}
