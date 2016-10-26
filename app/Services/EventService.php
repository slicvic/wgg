<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\Event;
use App\Models\EventVenue;

class EventService
{
    /**
     * Create new event.
     *
     * @param array $data
     * @return Event $event
     * @throws Exception
     */
    public function create(array $data)
    {
        DB::beginTransaction();

        try {
            $venue = EventVenue::create([
                'name' => $data['venue']['name'],
                'latlng' => array_values($data['venue']['latlng']),
                'address' => $data['venue']['address'],
                'url' => $data['venue']['url']
            ]);

            $event = Event::create([
                'user_id' => Auth::user()->id,
                'type_id' => $data['event']['type_id'],
                'status_id' => $data['event']['status_id'],
                'venue_id' => $venue->id,
                'title' => $data['event']['title'],
                'start_at' => date('Y-m-d H:i:s', strtotime($data['event']['start_at'])),
                'end_at' => date('Y-m-d H:i:s', strtotime($data['event']['start_at']) + ($data['event']['duration'] * 3600)),
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
     * Update existing event.
     *
     * @param  Event  $event
     * @param  array  $data
     */
    public function update(Event $event, array $data)
    {
        DB::transaction(function () use ($event, $data) {
            $event->venue->update([
                'name' => $data['venue']['name'],
                'latlng' => array_values($data['venue']['latlng']),
                'address' => $data['venue']['address'],
                'url' => $data['venue']['url']
            ]);

            $event->update([
                'type_id' => $data['event']['type_id'],
                'status_id' => $data['event']['status_id'],
                'title' => $data['event']['title'],
                'start_at' => date('Y-m-d H:i:s', strtotime($data['event']['start_at'])),
                'end_at' => date('Y-m-d H:i:s', strtotime($data['event']['start_at']) + ($data['event']['duration'] * 3600)),
                'description' => $data['event']['description']
            ]);
        });
    }
}
