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
}
