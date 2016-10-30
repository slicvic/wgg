@extends('layouts.default')

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

            <div class="list-group">

            @foreach ($events as $event)
                <a href="{{ route('events.show', ['id' => $event->id]) }}" class="list-group-item list-group-item-action">
                    <h5 class="list-group-item-heading">{{ $event->present()->title() }}</h5>
                    <p class="list-group-item-text"><i class="fa fa-map-marker"></i> {{ $event->venue->name }}</p>
                </a>
            @endforeach
            </div>


        </div>
    </div>
</div>

@endsection
