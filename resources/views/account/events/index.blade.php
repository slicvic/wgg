@extends('layouts.default')

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
                <td>{{ $event->present()->when('long') }}</td>
                <td>
                    @if ($event->isCanceled() || $event->hasPassed())
                        <a href="{{ route('events.getReschedule', ['id' => $event->id]) }}" class="btn btn-primary">Reschedule</a>
                    @else
                        <a href="{{ route('events.getEdit', ['id' => $event->id]) }}" class="btn btn-primary">Edit</a>
                        <a href="{{ route('events.cancel', ['id' => $event->id]) }}"
                            class="btn btn-danger"
                            v-on:click="cancelEvent">Cancel</a>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@else
<hr>
<p>Looks like you have not created any games.</p>
@endif
@endsection
