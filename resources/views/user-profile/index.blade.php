@extends('layouts.default')
@section('body-class', 'user-profile')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm-3">
            <img class="img-thumbnail" src="{{ $user->present()->profilePictureUrl(null, 300) }}" alt="{{ Auth::user()->name }}">
        </div>
        <div class="col-sm-8">
            <h1 class="display-4">
                {{ $user->name }}
                <small><i class="{{ $user->present()->socialAccountIconCssClass() }}"></i></small>
            </h1>

            @if ($user->location_name)
                <h4><i class="fa fa-map-marker"></i> <small>{{ $user->location_name }}</small><h4>
            @endif

            <hr>
            <h3 class="display-4 events-heading"><i class="fa fa-calendar"></i> My Games</h3>

            <div class="list-group events-list">
                @forelse ($events as $event)
                    <a href="{{ route('events.show', ['id' => $event->id]) }}" class="list-group-item list-group-item-action">
                        <h5 class="list-group-item-heading">{{ $event->present()->title() }}</h5>
                        <div class="media list-group-item-text">
                            <div class="media-left">
                                <i class="fa fa-clock-o"></i>
                            </div>
                            <div class="media-body">
                                {{ $event->present()->when('long') }}
                            </div>
                        </div>
                        <div class="media list-group-item-text">
                            <div class="media-left">
                                <i class="fa fa-map-marker"></i>
                            </div>
                            <div class="media-body">
                                {{ $event->venue->name }} <br>
                                {{ $event->venue->address }}
                            </div>
                        </div>
                    </a>
                @empty
                    No games scheduled.
                @endforelse
            </div>


        </div>
    </div>
</div>

@endsection
