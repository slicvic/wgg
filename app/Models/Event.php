<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Presenters\PresentableTrait;

class Event extends Model
{
    use PresentableTrait;

    /**
     * @inheritdoc
     */
    protected $presenterClassName = 'App\Presenters\EventPresenter';

    /**
     * @inheritdoc
     */
    protected $fillable = [
        'title',
        'description',
        'user_id',
        'type_id',
        'venue_id',
        'status_id',
        'start_at',
        'end_at'
    ];

    /**
     * @inheritdoc
     */
    protected $dates = [
        'start_at',
        'end_at'
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
     * Check if the event is active.
     *
     * @return bool
     */
    public function isActive()
    {
        return ($this->status_id == EventStatus::ACTIVE);
    }

    /**
     * Check if the event has passed.
     *
     * @return bool
     */
    public function hasPassed()
    {
        $now = time();
        $ending = strtotime($this->end_at);

        return ($now > $ending);
    }

    /**
     * Calculate the duration in hours.
     *
     * @return int
     */
    public function calculateDuration()
    {
        return (strtotime($this->end_at) - strtotime($this->start_at)) / 3600;
    }

    /**
     * Cancel the event.
     *
     * @return bool
     */
    public function cancel()
    {
        return static::cancelById($this->id);
    }

    /**
     * Find all events by user id.
     *
     * @param int $id
     * @return Event[]
     */
    public static function findAllByUserId($id)
    {
        $events = static::where(['user_id' => $id])
                ->orderBy('status_id', 'ASC')
                ->orderBy('start_at', 'DESC')
                ->get();

        return $events;
    }

    /**
     * Cancel event by id.
     *
     * @param int $id
     */
    public static function cancelById($id)
    {
        static::where('id', $id)->update(['status_id' => EventStatus::CANCELED]);
    }
}
