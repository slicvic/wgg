<?php

namespace App\Presenters;

class EventVenuePresenter extends BasePresenter
{
    /**
     * Get the static map image URL.
     *
     * @return string
     */
    public function staticMapImageUrl(int $width = 640, int $height = 100)
    {
        return implode('', [
            'https://maps.googleapis.com/maps/api/staticmap',
            "?center={$this->model->lat},{$this->model->lng}&zoom=15&size={$width}x{$height}&maptype=roadmap",
            "&markers=color:red%7C{$this->model->lat},{$this->model->lng}",
            '&key=' . env('GOOGLE_MAPS_API_KEY')
        ]);
    }
}
