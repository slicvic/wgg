<?php

namespace App\Presenters;

use Illuminate\Database\Eloquent\Model;

class EventPresenter extends BasePresenter
{
    /**
     * Calculate the duration in hours.
     *
     * @return int
     */
    public function startAt()
    {
        return date('m/d/Y g:i A', strtotime($this->model->start_at));
    }

    /**
     * Calculate the duration in hours.
     *
     * @return int
     */
    public function duration()
    {
        return (strtotime($this->model->end_at) - strtotime($this->model->start_at)) / 3600;
    }
}
