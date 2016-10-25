<?php

namespace App\Presenters;

use App\Models\EventStatus;

class EventPresenter extends BasePresenter
{
    /**
     * Present title.
     *
     * @return string
     */
    public function title()
    {
        return ucwords(strtolower($this->model->title));
    }

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
     * Present status as plain text or bootstrap tag.
     *
     * @param bool $asTag
     * @return string
     */
    public function status($asTag = false)
    {
        switch ($this->model->status->id) {
            case EventStatus::ACTIVE:
                if ($this->model->hasPassed()) {
                    return ($asTag) ? '<span class="tag tag-warning">Passed</span>' : 'Passed';
                }

                return ($asTag) ? '<span class="tag tag-success">On</span>' : 'On';

            default:
                return ($asTag) ? '<span class="tag tag-danger">Canceled</span>' : 'Canceled';
        }
    }

    /**
     * Present duration in hours.
     *
     * @return int
     */
    public function duration()
    {
        $duration = $this->model->calculateDuration();
        $duration .= ($duration > 1) ? ' hours' : ' hour';
        return $duration;
    }
}
