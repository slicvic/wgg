@extends('layouts.default.layout')
@section('body-class', 'homxe')

@section('content')
    <div class="jumbotron">
        <h1 class="display-4">Who's Got Game!</h1>
        <p class="lead">Find and organize pickup games.</p>
        <hr>
        <form class="form-inline events-inline-search-form">
            <div class="form-group">
                <input type="text" class="form-control form-control-lg"
                    name="q"
                    size="25"
                    placeholder="All games">
            </div>
            <div class="form-group">
                <label>within</label>
                <select class="form-control form-control-lg" name="within_miles">
                    @for ($i = 5; $i <= 50; $i+=5)
                        <option value="{{ $i }}">{{ $i }} miles</option>
                    @endfor
                </select>
            </div>
            <div class="form-group">
                <label>of</label>
                <input type="hidden" name="lat" id="events-inline-search-form--lat" value="{{ $geolocation['lat'] }}">
                <input type="hidden" name="lng" id="events-inline-search-form--lng" value="{{ $geolocation['lng'] }}">
                <input type="text"
                    name="city"
                    class="form-control form-control-lg js-typeahead-city"
                    value="{{ $geolocation['city'] }}"
                    data-bind-field-lat="#events-inline-search-form--lat"
                    data-bind-field-lng="#events-inline-search-form--lng">
            </div>
            <button type="submit" class="btn btn-primary btn-lg">Search</button>
        </form>
    </div>

    <div class="card-columns">

    @foreach ($events as $event)
        @include('partials.events.card', compact('event'))
    @endforeach
</div>
@endsection
