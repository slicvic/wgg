@extends('layouts.default')

@section('content')
<table class="table table-striped">
    <thead>
        <tr>
            <th>Title</th>
            <th>Type</th>
            <th>Status</th>
            <th>Where</th>
            <th>From</th>
            <th>To</th>
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
                <td>{{ $event->start_at }}</td>
                <td>{{ $event->end_at }}</td>
                <td>{{ $event->present()->duration() }}</td>
                <td><a href="{{ route('events.edit', ['id' => $event->id]) }}" class="btn btn-primary">Edit</button></td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
