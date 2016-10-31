@extends('layouts.default')
@section('body-class', 'user-profile')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-4">
                <div class="card text-xs-center profile-info">
                    <div class="card-block">
                        <h1 class="card-title display-6">
                            {{ $user->name }}
                            <small><i class="{{ $user->present()->socialAccountIconCssClass() }}"></i></small>
                        </h1>
                        <p class="card-text">
                            <img class="img-thumbnail" src="{{ $user->present()->profilePictureUrl(200) }}" alt="{{ $user->name }}">
                        </p>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <i class="fa fa-user"></i>
                            Joined on {{ $user->present()->joinDate() }}
                        </li>
                        @if ($user->location_name)
                            <li class="list-group-item">
                                <i class="fa fa-map-marker"></i>
                                {{ $user->location_name }}
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
            <div class="col-sm-8">
                <h3 class="display-5"><i class="fa fa-calendar"></i> My Games</h3>
                <hr>
                <div class="list-group">
                    @forelse ($events as $event)
                        <a href="{{ route('events.show', ['id' => $event->id]) }}" class="list-group-item list-group-item-action">
                            <h5 class="list-group-item-heading">
                                {{ $event->present()->title() }}
                                <span class="tag tag-default">{{ $event->type->label }}</span>
                                @if ($event->isCanceled() || $event->hasPassed()){!! $event->present()->status(true) !!}@endif
                            </h5>
                            <div class="list-group-item-text">
                                <div class="media">
                                    <div class="media-left">
                                        <i class="fa fa-clock-o"></i>
                                    </div>
                                    <div class="media-body">
                                        {{ $event->present()->when('long') }}
                                    </div>
                                </div>
                            </div>
                            <div class="list-group-item-text">
                                <div class="media">
                                    <div class="media-left">
                                        <i class="fa fa-map-marker"></i>
                                    </div>
                                    <div class="media-body">
                                        <div><strong>{{ $event->venue->name }}</strong></div>
                                        <div>{{ $event->venue->address }}</div>
                                    </div>
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
