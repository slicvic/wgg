@extends('layouts.default.layout')
@section('body-class', 'home-page')

@section('content')
    <div class="jumbotron">
        <h1 class="display-4">Who's Got Game!</h1>
        <p class="lead">Find and organize pickup games.</p>
        <hr>
        @include('partials.events.form-search-v1', ['input' => $input])
    </div>

    <div class="container">
        <div class="row">
            <h4 class="pull-left">Upcoming Games nearby</h4>
            <a class="pull-right" href="{{ route('search.events') }}">See all</a>
        </div>
    </div>
    <hr>
    <div class="card-deck-wrapper">
        <div class="card-deck">
            @forelse ($events as $event)
                @include('partials.events.card', compact('event'))
            @empty
                <p>No games found nearby.</p>
            @endforelse
        </div>
    </div>
@endsection
