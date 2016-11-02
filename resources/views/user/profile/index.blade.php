@extends('layouts.default.layout')
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
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#upcoming" role="tab">Upcoming</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#past" role="tab">Past</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="upcoming" role="tabpanel">
                        <br>
                        <div class="list-group">
                            @forelse (array_merge($events['upcoming'], $events['canceled']) as $event)
                                @include('user.profile.partials.event-list-group-item', compact('event'))
                            @empty
                                No upcoming games.
                            @endforelse
                        </div>
                    </div>
                    <div class="tab-pane" id="past" role="tabpanel">
                        <br>
                        <div class="list-group">
                            @forelse ($events['past'] as $event)
                                @include('user.profile.partials.event-list-group-item', compact('event'))
                            @empty
                                No past games.
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
