<?php

namespace App\Services;

use InvalidArgumentException;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\Event;
use App\Models\EventVenue;
use App\Models\EventStatus;

class EventService
{
    /**
     * Create a new event.
     *
     * @param array $data
     * @return Event
     * @throws Exception
     */
    public function create(array $data)
    {
        DB::beginTransaction();

        try {
            // Create venue
            $venue = EventVenue::create([
                'name' => $data['venue']['name'],
                'lat' => $data['venue']['lat'],
                'lng' => $data['venue']['lng'],
                'address' => $data['venue']['address'],
                'url' => $data['venue']['url']
            ]);

            // Create event
            $event = Event::create([
                'user_id' => $data['event']['user_id'],
                'type_id' => $data['event']['type_id'],
                'status_id' => $data['event']['status_id'],
                'venue_id' => $venue->id, // Set venue id
                'title' => $data['event']['title'],
                'start_at' => $data['event']['start_at'],
                'description' => $data['event']['description']
            ]);

            DB::commit();
            return $event;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update an existing event.
     *
     * @param  Event  $event
     * @param  array  $data
     * @throws Exception
     */
    public function update(Event $event, array $data)
    {
        DB::beginTransaction();

        try {
            // Update venue
            if (is_array($data['venue']) && count($data['venue'])) {
                $event->venue->update($data['venue']);
            }

            // Update event
            if (is_array($data['event']) && count($data['event'])) {
                $event->update($data['event']);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Reschedule an existing event.
     *
     * @param  Event  $oldEvent
     * @param  array  $data
     * @return Event
     * @throws Exception
     */
    public function reschedule(Event $oldEvent, array $data)
    {
        DB::beginTransaction();

        try {
            // Recreate venue
            $newVenue = $oldEvent->venue->replicate();
            if (is_array($data['venue']) && count($data['venue'])) {
                $newVenue->fill($data['venue']);
            }
            $newVenue->save();

            // Recreate event
            $newEvent = $oldEvent->replicate();
            if (is_array($data['event']) && count($data['event'])) {
                $newEvent->fill($data['event']);
            }
            $newEvent->status_id = EventStatus::ACTIVE;
            $newEvent->venue_id = $newVenue->id;
            $newEvent->save();

            DB::commit();
            return $newEvent;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
