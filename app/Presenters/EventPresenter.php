<?php

namespace App\Presenters;

use App\Models\EventStatus;

class EventPresenter extends BasePresenter
{
    /**
     * Present start date time.
     *
     * @param bool $verbose
     * @return string
     */
    public function when($verbose = false)
    {
        $format = ($verbose) ? 'l, M j, Y g:i A' : 'm/d/Y g:i A';
        return date($format, strtotime($this->model->start_at));
    }

    /**
     * Present status.
     *
     * @param bool $asHtml
     * @return string
     */
    public function status($asHtml = false)
    {
        switch ($this->model->status->id) {
            case EventStatus::ACTIVE:
                if ($this->model->hasPassed()) {
                    return ($asHtml) ? '<span class="label label-warning">Passed</span>' : 'Passed';
                }

                return ($asHtml) ? '<span class="label label-success">Active</span>' : 'Active';

            default:
                return ($asHtml) ? '<span class="label label-danger">Canceled</span>' : 'Canceled';
        }
    }

    /**
     * Present duration in hours.
     *
     * @return int
     */
    public function duration()
    {
        return (strtotime($this->model->end_at) - strtotime($this->model->start_at)) / 3600;
    }
}
