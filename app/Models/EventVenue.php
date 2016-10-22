<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use InvalidArgumentException;

class EventVenue extends Model
{
    /**
     * @inheritdoc
     */
    protected $fillable = [
        'name',
        'address',
        'latlng'
    ];

    /**
     * Set latlng attribute.
     *
     * @param  array|null $value
     * @throws InvalidArgumentException
     */
    public function setLatlngAttribute($value)
    {
        if ($value === null) {
            $this->attributes['latlng'] = null;
        } else if (is_array($value) && count($value) === 2) {
            $this->attributes['latlng'] = DB::raw("GeomFromText('POINT({$value[0]} {$value[1]})')");
        } else {
            throw new InvalidArgumentException('value must be null or array in the form of: [lat, lng].');
        }
    }

    /**
     * Get latlng attribute.
     *
     * @param  array|null $value
     * @return array|null Array in the form of [lat, lng] or null.
     */
    public function getLatlngAttribute($value)
    {
        if (empty($value)) {
            return null;
        }

        $value = str_replace(['POINT(', ')'], '', $value);

        return explode(' ', $value);
    }

    /**
     * Override to convert latlng to string.
     */
    public function newQuery()
    {
        $builder = parent::newQuery();

        if (is_null($builder->getQuery()->columns)) {
            $builder->getQuery()->columns = ['*'];
        }

        $builder->getQuery()->selectRaw('ST_AsText(latlng) AS latlng');

        return $builder;
    }
}
