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
     * Check if a given user is the organizer.
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
     * Scope a query to only include events nearby.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  double $lat
     * @param  double $lng
     * @param  int $withinMiles
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterNearby($query, $lat, $lng, $withinMiles = 50)
    {
        $query->select('events.*');

        if (!$this->isJoined($query, 'event_venues')) {
            $query->join('event_venues', 'events.venue_id', '=', 'event_venues.id');
        }

        /**
         * Multiply by 6371 (earth's radius in KM) to get distance in KM
         * Then, multiply by 0.621371 to convert KM to miles (1 KM = 0.621371 miles)
         */
        $query->whereRaw('
            (
                ACOS(
                    COS(RADIANS(?)) * COS(RADIANS(event_venues.lat)) *
                    COS(RADIANS(event_venues.lng) - RADIANS(?)) + SIN(RADIANS(?)) * SIN(RADIANS(event_venues.lat))
                ) * 6371 * 0.621371
            ) <= ?
        ', [$lat, $lng, $lat, $withinMiles]
        );

        return $query;
    }

    /**
     * Scope a query to only include active events.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterActive($query)
    {
        return $query->where('events.status_id', EventStatus::ACTIVE);
    }

    /**
     * Scope a query to only include events of given types.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterTypes($query, array $typeIds)
    {
        return $query->whereIn('events.type_id', $typeIds);
    }

    /**
     * Scope a query to only include upcoming events.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterUpcoming($query)
    {
        return $query->whereDate('events.start_at', '>=', date('Y-m-d'));
    }

    /**
     * Scope a query to only include events matching search term.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string $text
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterKeywords($query, $keywords)
    {
        $query->select('events.*');

        if (!$this->isJoined($query, 'event_types')) {
            $query->join('event_types', 'events.type_id', '=', 'event_types.id');
        }

        if (!$this->isJoined($query, 'event_venues')) {
            $query->join('event_venues', 'events.venue_id', '=', 'event_venues.id');
        }

        $query->where(function ($query) use ($keywords) {
            $query
                ->where('events.title', 'LIKE', '%' . $keywords . '%')
                ->orWhere('events.description', 'LIKE', '%' . $keywords . '%')
                ->orWhere('event_venues.name', 'LIKE', '%' . $keywords . '%')
                ->orWhere('event_venues.address', 'LIKE', '%' . $keywords . '%')
                ->orWhere('event_types.name', 'LIKE', '%' . $keywords . '%');
        });

        return $query;
    }

    /**
     * Scope a query to order by the closest start datetime.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderBySoonest($query)
    {
        return $query->orderBy('events.start_at', 'ASC');
    }

    /**
     * Find all events by a given user id.
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
     * Check if a table is already joined in query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string $table
     * @return bool
     */
    private function isJoined($query, $table)
    {
        $joins = $query->getQuery()->joins;

        if (!$joins) {
            return false;
        }

        foreach ($joins as $join) {
            if ($table == $join->table) {
                return true;
            }
        }

        return false;
    }
}
