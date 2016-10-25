<?php

namespace App\Presenters;

use App\Models\EventStatus;

class EventPresenter extends BasePresenter
{
    /**
     * Present the start date time.
     *
     * @return string
     */
    public function start()
    {
        return date('m/d/Y g:i A', strtotime($this->model->start_at));
    }

    /**
     * Present the status.
     *
     * @param bool $asHtml
     * @return string
     */
    public function status($asHtml = false)
    {
        $now = time();
        $ending = strtotime($this->model->end_at);

        switch ($this->model->status->id) {
            case EventStatus::ACTIVE:
                if ($now > $ending) {
                    return ($asHtml) ? '<span class="label label-warning">Passed</span>' : 'Passed';
                }
                return ($asHtml) ? '<span class="label label-success">Active</span>' : 'Active';
            default:
                return ($asHtml) ? '<span class="label label-danger">Canceled</span>' : 'Canceled';
        }
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
