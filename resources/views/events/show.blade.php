@extends('layouts.default.layout')
@section('body-class', 'event-page')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-9">
                <div class="card">
                    <div class="card-block event-title">
                        <h1 class="card-title display-4">
                            {{ $event->present()->title() }}
                            @if ($event->isCanceled()){!! $event->present()->status(true) !!}@endif
                            @if ($event->isOrganizer(Auth::user()))
                                <a href="{{ route('events.edit', ['id' => $event->id]) }}" class="btn btn-link"><i class="fa fa-pencil"></i> Edit</a>
                            @endif
                        </h1>
                        <h6 class="card-subtitle text-muted">{{ $event->type->label }}</h6>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <div class="media">
                                <div class="media-left">
                                    <i class="fa fa-clock-o"></i>
                                </div>
                                <div class="media-body">
                                    <div>{{ $event->present()->when('long') }}</div>
                                    <div class="text-warning">{{ $event->present()->when('diff') }}</div>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="media">
                                <div class="media-left">
                                    <i class="fa fa-map-marker"></i>
                                </div>
                                <div class="media-body">
                                    <a target="_blank" href="{{ $event->venue->url }}">{{ $event->venue->name }}</a>
                                    <div>{{ $event->venue->address }}</div>
                                    <a target="_blank" href="{{ $event->venue->url }}">
                                        <img src="{{$event->venue->present()->staticMapImageUrl()}}">
                                    </a>
                                </div>
                            </div>
                        </li>
                        @if ($event->description)
                            <li class="list-group-item">
                                <div class="media">
                                    <div class="media-left">
                                        <i class="fa fa-info"></i>
                                    </div>
                                    <div class="media-body">
                                        {!! $event->present()->description() !!}
                                    </div>
                                </div>
                            </li>
                        @endif
                    </ul>
                </div>
                <div class="card">
                    <div class="card-block">
                        <div class="fb-comments" data-href="{{ route('events.show', ['id' => $event->id])}}" data-numposts="30" data-width="100%">
                            Loading comments...
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card text-xs-center">
                    <div class="card-block">
                        <h4 class="card-title">{{ $event->user->name }}</h4>
                        <h6 class="card-subtitle text-muted">Organizer</h6>
                    </div>
                    <img class="img-thumbnail" src="{{ $event->user->present()->profilePictureUrl(100) }}" alt="{{ $event->user->name }}">
                    <br><br>
                    <div class="card-footer text-muted">
                        <a href="{{ route('user.profile', ['id' => $event->user->id]) }}" class="card-link">Profile</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
