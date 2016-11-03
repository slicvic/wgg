<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Presenters\PresentableTrait;

class Event extends Model
{
    use SoftDeletes, PresentableTrait;

    /**
     * The number of hours after start time an event must have to be considered "past due".
     */
    const PAST_DUE_THRESHOLD = 8;

    /**
     * {@inheritdoc}
     */
    protected $presenterClassName = 'App\Presenters\EventPresenter';

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'title',
        'description',
        'user_id',
        'type_id',
        'venue_id',
        'status_id',
        'start_at'
    ];

    /**
     * {@inheritdoc}
     */
    protected $dates = [
        'start_at',
        'deleted_at'
    ];

    /**
     * Get the status record associated with the event.
     *
     * @return \App\Models\EventStatus
     */
    public function status()
    {
        return $this->hasOne('App\Models\EventStatus', 'id', 'status_id');
    }

    /**
     * Get the user record associated with the event.
     *
     * @return \App\Models\EventType
     */
    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    /**
     * Get the type record associated with the event.
     *
     * @return \App\Models\EventType
     */
    public function type()
    {
        return $this->hasOne('App\Models\EventType', 'id', 'type_id');
    }

    /**
     * Get the venue record associated with the event.
     *
     * @return \App\Models\EventVenue
     */
    public function venue()
    {
        return $this->hasOne('App\Models\EventVenue', 'id', 'venue_id');
    }

    /**
     * Check if the event is canceled.
     *
     * @return bool
     */
    public function isCanceled()
    {
        return ($this->status_id == EventStatus::CANCELED);
    }

    /**
     * Check if the event has passed.
     *
     * @return bool
     */
    public function hasPassed()
    {
        // Check if more than X hours have passed since the event started
        $now = time();
        $start = strtotime($this->start_at);

        return (($now - $start) > (3600 * static::PAST_DUE_THRESHOLD));
    }

    /**
     * Check if the given user is the organizer.
     *
     * @param int $user
     * @return bool
     */
    public function isOrganizer(\App\Models\User $user)
    {
        return ($this->user_id === $user->id);
    }

    /**
     * Cancel the event.
     *
     * @return bool
     */
    public function cancel()
    {
        $this->status_id = EventStatus::CANCELED;
        return $this->save();
    }

    /**
     * Set start datetime attribute.
     *
     * @param string $value
     */
    public function setStartAtAttribute($value)
    {
        $this->attributes['start_at'] = date('Y-m-d H:i:s', strtotime($value));
    }

    /**
     * Find all events by the given user id.
     *
     * @param int $id
     * @return Event[]
     */
    public static function findAllByUserId($id)
    {
        $events = static::where(['user_id' => $id])
                ->orderBy('start_at', 'ASC')
                ->get();

        $grouped = [
            'upcoming' => [],
            'canceled' => [],
            'past' => []
        ];

        foreach ($events as $event) {
            if ($event->hasPassed()) {
                $grouped['past'][] = $event;
            } else if ($event->isCanceled()) {
                $grouped['canceled'][] = $event;
            } else {
                $grouped['upcoming'][] = $event;
            }
        }

        return $grouped;
    }

    /**
     * Find all events by the given criteria.
     *
     * @param  array $criteria [description]
     * @return Event[]
     */
    public static function createSearchQuery(array $criteria = [])
    {
        $query = Event::query()
                        ->select('events.*')
                        ->orderBy('events.start_at', 'ASC')
                        ->where('events.status_id', EventStatus::ACTIVE)
                        ->whereDate('events.start_at', '>=', date('Y-m-d'));

        if (!empty($criteria['q'])) {
            $query
                ->join('event_types', 'events.type_id', '=', 'event_types.id')
                ->join('event_venues', 'events.venue_id', '=', 'event_venues.id')
                ->where(function ($query) use ($criteria) {
                    $query
                        ->where('events.title', 'LIKE', '%' . $criteria['q'] . '%')
                        ->orWhere('events.description', 'LIKE', '%' . $criteria['q'] . '%')
                        ->orWhere('event_types.name', 'LIKE', '%' . $criteria['q'] . '%')
                        ->orWhere('event_venues.name', 'LIKE', '%' . $criteria['q'] . '%');
                });
        }

        if (!(empty($criteria['near']['lat']) && empty($criteria['near']['lng']) && empty($criteria['near']['within_miles']))) {

            if (!empty($criteria['q'])) {
                $query->join('event_venues', 'events.venue_id', '=', 'event_venues.id');
            }

            /**
             * Multiply by 6371 (earth's radius in KM) to get distance in KM
             * Then, multiply by 0.621371 to convert KM to miles (1 KM = 0.621371 miles)
             */
            $query->selectRaw('
                (
                    ACOS(
                        COS(RADIANS(?)) * COS(RADIANS(event_venues.lat)) *
                        COS(RADIANS(event_venues.lng) - RADIANS(?)) + SIN(RADIANS(?)) * SIN(RADIANS(event_venues.lat))
                    ) * 6371 * 0.621371
                ) AS distance
            ', [$criteria['near']['lat'], $criteria['near']['lng'], $criteria['near']['lat']]
            )
            ->having('distance', '<=', $criteria['near']['within_miles']);
        }

        return $query;
    }
}
