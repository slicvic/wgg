<?php

namespace App\Presenters;

use App\Models\EventStatus;

class EventPresenter extends BasePresenter
{
    /**
     * Present the start date time.
     *
     * @param string $format
     * @return string
     */
    public function start($format = 'm/d/Y g:i A')
    {
        return date($format, strtotime($this->model->start_at));
    }

    /**
     * Present the status.
     *
     * @param bool $asHtml
     * @return string
     */
    public function status($asHtml = false)
    {
        switch ($this->model->status->id) {
            case EventStatus::ACTIVE:
                $now = time();
                $ending = strtotime($this->model->end_at);

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
