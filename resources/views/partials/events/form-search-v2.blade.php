<form class="" method="get" action="{{ route('search.events') }}">
    <div class="form-group">
        <input type="text" class="form-control form-control-lg"
            name="keywords"
            size="25"
            value="{{ $input['keywords'] or '' }}"
            placeholder="All Games">
    </div>
    <div class="form-group">
        <label>Distance</label>
        <select class="form-control form-control-lg" name="radius">
            @include('partials.events.radius-select-options', ['selectedValue' => $input['radius']])
        </select>
    </div>
    <div class="form-group">
        <label>From</label>
        <input type="hidden" name="lat" id="events-search-form--lat" value="{{ $input['lat'] or '' }}">
        <input type="hidden" name="lng" id="events-search-form--lng" value="{{ $input['lng'] or '' }}">
        <input type="text"
            name="city"
            class="form-control form-control-lg js-typeahead-city"
            value="{{ $input['city'] or '' }}"
            data-bind-field-lat="#events-search-form--lat"
            data-bind-field-lng="#events-search-form--lng">
    </div>
    <div class="form-group">
        <p>Sport</p>
        @foreach (\App\Models\EventType::all() as $eventType)
            <div class="form-check">
                <label class="form-check-label">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="type[]"
                        value="{{ $eventType->id }}"
                        {{ ($input['type'] && in_array($eventType->id, $input['type'])) ? ' checked' : '' }}> 
                        {{ $eventType->label }}
                </label>
            </div>
        @endforeach
    </div>

    <button type="submit" class="btn btn-primary btn-lg">Search</button>
</form>
