<?php
    switch (Route::currentRouteName()) {
        case 'events.edit':
            $formAction = route('events.postEdit', ['id' => $event->id]);
            break;
        case 'events.reschedule':
            $formAction = route('events.postReschedule', ['id' => $event->id]);
            break;
        default:
            $formAction = route('events.postCreate');
    }
 ?>
<form action="{{ $formAction }}" method="post" id="events--create-edit-form" class="js-validate-form">
    <div v-html="errors"></div>
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">
            Choose a title
            <small class="form-text text-muted">Something that fits your game.</small>
        </label>
        <div class="col-sm-10">
            <input
                type="text"
                name="event[title]"
                class="form-control"
                placeholder="e.g. Soccer Sundays"
                value="{{ $event->present()->title() }}"
                data-parsley-required-message="Choose a title that fits your game e.g. Soccer Sundays"
                required>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">
            Type of game?
            <small class="form-text text-muted">Which game do you want to play?</small>
        </label>
        <div class="col-sm-10">
            <select
                name="event[type_id]"
                class="form-control"
                data-parsley-required-message="Which game do you want to play?"
                required>
                <option value=""></option>
                @foreach (\App\Models\EventType::all() as $type)
                    <option value="{{ $type->id }}"{{ ($event->type_id == $type->id) ? ' selected' : '' }}>{{ $type->label }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">
            Where's the game?
            <small class="form-text text-muted">Find a place to play.</small>
        </label>
        <div class="col-sm-10">
            <input type="hidden" name="venue[lat]" id="venue-lat" value="{{ ($event->exists) ? $event->venue->lat : '' }}">
            <input type="hidden" name="venue[lng]" id="venue-lng" value="{{ ($event->exists) ? $event->venue->lng : '' }}">
            <input type="hidden" name="venue[address]" id="venue-address" value="{{ ($event->exists) ? $event->venue->address : '' }}">
            <input type="hidden" name="venue[url]" id="venue-url" value="{{ $event->exists ? $event->venue->url : '' }}">
            <input
                type="text"
                name="venue[name]"
                class="form-control js-typeahead-venue"
                value="{{ ($event->exists) ? $event->venue->name : '' }}"
                required
                placeholder="e.g. Wilde Park"
                data-parsley-required-message="Find a place to play."
                data-bind-field-lat="#venue-lat"
                data-bind-field-lng="#venue-lng"
                data-bind-field-address="#venue-address"
                data-bind-field-url="#venue-url">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">
            Start time?
            <small class="form-text text-muted">Choose a day and time.</small>
        </label>
        <div class="col-sm-10">
            <input
                type="text"
                name="event[start_at]"
                placeholder="e.g. 10/08/2016 1:00 PM"
                class="form-control js-datetimepicker"
                value="{{ ($event->exists) ? date('m/d/Y g:i A', strtotime($event->start_at)) : '' }}"
                data-parsley-required-message="Choose a day and time."
                required>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">
            More details
            <small class="form-text text-muted">Tell people more about the game.</small>
        </label>
        <div class="col-sm-10">
            <textarea name="event[description]" class="form-control" rows="8" cols="40">{{ $event->description }}</textarea>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label"></label>
        <div class="col-sm-10">
            <a href="{{ route('user.account.events.index') }}" class="btn btn-default">Cancel</a>
            <button
                type="submit"
                class="btn btn-primary btn-lg"
                v-bind:disabled="submitted"
                v-html="submitButtonText">Save</button>
        </div>
    </div>
</form>
