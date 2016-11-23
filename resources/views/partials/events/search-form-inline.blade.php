<form class="form-inline events-inline-search-form" method="get" action="{{ route('search.events') }}">
    <div class="form-group">
        <input type="text" class="form-control form-control-lg"
            name="keyword"
            size="25"
            value="{{ $input['keyword'] or '' }}"
            placeholder="All Games">
    </div>
    <div class="form-group">
        <label>within</label>
        <select class="form-control form-control-lg" name="distance">
            @include('partials.events.distance-select-options', ['selectedValue' => $input['distance']])
        </select>
    </div>
    <div class="form-group">
        <label>of</label>
        <input type="hidden" name="lat" id="events-inline-search-form--lat" value="{{ $input['lat'] or '' }}">
        <input type="hidden" name="lng" id="events-inline-search-form--lng" value="{{ $input['lng'] or '' }}">
        <input type="text"
            name="city"
            class="form-control form-control-lg js-typeahead-city"
            value="{{ $input['city'] or '' }}"
            data-bind-field-lat="#events-inline-search-form--lat"
            data-bind-field-lng="#events-inline-search-form--lng">
    </div>
    <button type="submit" class="btn btn-primary btn-lg">Search</button>
</form>
