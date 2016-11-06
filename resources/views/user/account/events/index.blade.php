@extends('layouts.default.layout')

@section('content')
    <h1 class="display-4">
        My games
        <small class="text-muted">Create and manage your games</small>
    </h1>

    @if (count($events))
        <table id="events--my-events" class="table table-striped">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Where</th>
                    <th>When</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($events as $event)
                    <tr>
                        <td>{{ $event->present()->title() }}</td>
                        <td>{{ $event->type->label }}</td>
                        <td>{!! $event->present()->status(true) !!}</td>
                        <td>{{ $event->venue->name }}</td>
                        <td>{{ $event->present()->date('long') }} <br> {{ $event->present()->time() }}</td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Action <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    @if ($event->isCanceled() || $event->hasPassed())
                                        <li><a href="{{ route('events.reschedule', ['id' => $event->id]) }}">Reschedule</a></li>
                                    @else
                                        <li><a href="{{ route('events.edit', ['id' => $event->id]) }}">Edit</a></li>
                                        <li><a href="{{ route('events.cancel', ['id' => $event->id]) }}" v-on:click="cancelEvent">Cancel</a></li>
                                    @endif
                                    <li role="separator" class="divider"></li>
                                    <li><a href="{{ route('events.show', ['id' => $event->id]) }}">View</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <hr>
        <p>You have not created any games.</p>
    @endif
@endsection
