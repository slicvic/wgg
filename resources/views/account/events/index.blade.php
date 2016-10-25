@extends('layouts.default')

@section('content')
<table class="table table-striped">
    <thead>
        <tr>
            <th>Title</th>
            <th>Type</th>
            <th>Status</th>
            <th>Where</th>
            <th>When</th>
            <th>Duration</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($events as $event)
            <tr>
                <td>{{ $event->title }}</td>
                <td>{{ $event->type->title }}</td>
                <td>{!! $event->present()->status(true) !!}</td>
                <td>{{ $event->venue->name }}</td>
                <td>{{ $event->present()->when(true) }}</td>
                <td>{{ $event->end_at }}</td>
                <td>{{ $event->present()->duration() }}</td>
                <td>
                    <a href="{{ route('events.edit', ['id' => $event->id]) }}" class="btn btn-primary">Edit</button>
                    @if ($event->isActive() && !$event->hasPassed())
                        <a
                            href="{{ route('events.cancel', ['id' => $event->id]) }}"
                            class="btn btn-danger"
                            v-on:click="cancelEvent">Cancel</button>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
