<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Presenters\PresentableTrait;

class Event extends Model
{
    use SoftDeletes, PresentableTrait;

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
        // Check if more than X hours have passed since the game started
        $hoursPassed = 8;
        $now = time();
        $start = strtotime($this->start_at);

        return (($now - $start) > (3600 * $hoursPassed));
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
    public static function search(array $criteria = [])
    {
        $query = Event::query();
        $query->orderBy('start_at', 'ASC');

        if (!empty($criteria['type_id'])) {
            $query->where('type_id', $criteria['type_id']);
        }

        if (!empty($criteria['status_id'])) {
            $query->where('status_id', $criteria['status_id']);
        }

        //$criteria['within_miles'] = 10000;
        //$criteria['lat'] = '25.844725';
        //$criteria['lng'] = '-80.179466';

        if (!(empty($criteria['within_miles']) && empty($criteria['lat']) && empty($criteria['lng']))) {
            // Multiply by 6371 (earth's radius in KM) to get distance in KM
            // Then, multiply by 0.621371 to convert KM to miles (1 KM = 0.621371 miles)
            $query->selectRaw('
                (
                    ACOS(
                        COS(RADIANS(?)) * COS(RADIANS(event_venues.lat)) *
                        COS(RADIANS(event_venues.lng) - RADIANS(?)) + SIN(RADIANS(?)) * SIN(RADIANS(event_venues.lat))
                    ) * 6371 * 0.621371
                ) AS computed_distance
            ', [$criteria['lat'], $criteria['lng'], $criteria['lat']])
            ->join('event_venues', 'events.venue_id', '=', 'event_venues.id')
            ->having('computed_distance', '<=', $criteria['within_miles']);
        }

        $result = $query->get();

        return $result;
    }
}
