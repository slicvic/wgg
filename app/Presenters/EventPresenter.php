<?php

namespace App\Presenters;

use Carbon\Carbon;
use App\Models\EventStatus;

class EventPresenter extends BasePresenter
{
    /**
     * Present the title.
     *
     * @return string
     */
    public function title()
    {
        return ucwords(strtolower($this->model->title));
    }

    /**
     * Present the start date.
     *
     * @param string $type short|medium|long
     * @return string
     */
    public function date($type = 'long')
    {
        // $now = \Carbon\Carbon::createFromTimeStamp(strtotime(\Carbon\Carbon::now('America/New_York')));
        // $start = \Carbon\Carbon::createFromTimeStamp(strtotime($this->model->start_at));
        // $diffForHumans =  $start->diffForHumans($now);
        // $diffForHumans = str_replace(['before', 'after'], ['ago', 'from now'], $diffForHumans);
        $timestamp = strtotime($this->model->start_at);
        $carbon = Carbon::createFromTimeStamp($timestamp);
        $date = date('l, F j, Y', $timestamp);
        $humanDay = null;
        if ($carbon->isToday()) {
            $humanDay = 'Today';
        } elseif ($carbon->isTomorrow()) {
            $humanDay = 'Tomorrow';
        } elseif ($carbon->isYesterday()) {
            $humanDay = 'Yesterday';
        }

        switch ($type) {
            case 'short':
                return ($humanDay) ?: $date;
            case 'medium':
                return $date;
            case 'long':
            default:
                return ($humanDay) ? $humanDay . ', ' . $date : $date;
        }
    }

    /**
     * Present the start time.
     *
     * @return string
     */
    public function time()
    {
        return date('g:i A', strtotime($this->model->start_at));
    }

    /**
     * Present the status as plain text or bootstrap tag.
     *
     * @param bool $asHtml
     * @return string
     */
    public function status($asHtml = false)
    {
        switch ($this->model->status->id) {
            case EventStatus::ACTIVE:
                if ($this->model->hasPassed()) {
                    return ($asHtml) ? '<span class="tag tag-warning">Passed</span>' : 'Passed';
                }
                return ($asHtml) ? '<span class="tag tag-success">On</span>' : 'On';
            default:
                return ($asHtml) ? '<span class="tag tag-danger">Canceled</span>' : 'Canceled';
        }
    }

    /**
     * Present the description.
     *
     * @return string
     */
    public function description()
    {
        return nl2br($this->model->description);
    }
}
