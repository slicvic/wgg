<?php

namespace App\Policies;

use Illuminate\Auth\Access\AuthorizationException;

use App\Models\User;
use App\Models\Event;

class EventPolicy
{
    /**
     * Determine if the given user can create events.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine if the given event can be updated by the user.
     *
     * @param User $user
     * @param Event $event
     * @throws Exception
     */
    public function update(User $user, Event $event)
    {
        if (!$event->isOrganizer($user)) {
            throw new AuthorizationException(trans('messages.event.not_your_own'));
        }

        if ($event->isCanceled()) {
            throw new AuthorizationException(trans('messages.event.cannot_edit_canceled'));
        }

        if ($event->hasPassed()) {
            throw new AuthorizationException(trans('messages.event.cannot_edit_past'));
        }

        return true;
    }

    /**
     * Determine if the given event can be rescheduled by the user.
     *
     * @param User $user
     * @param Event $event
     * @throws Exception
     */
    public function reschedule(User $user, Event $event)
    {
        if (!$event->isOrganizer($user)) {
            throw new AuthorizationException(trans('messages.event.not_your_own'));
        }

        if (!($event->isCanceled() || $event->hasPassed())) {
            throw new AuthorizationException(trans('messages.event.cannot_reschedule_active'));
        }

        return true;
    }
}
