@extends('layouts.default')

@section('content')
<table class="table">
    <thead>
        <tr>
            <td>Title</td>
            <td>Type</td>
            <td>Status</td>
            <td>Where</td>
            <td>From</td>
            <td>To</td>
            <td>Duration</td>
            <td>Actions</td>
        </tr>
    </thead>
    <tbody>
        @foreach ($events as $event)
            <tr>
                <td>{{ $event->title }}</td>
                <td>{{ $event->type->title }}</td>
                <td>{{ $event->status->title }}</td>
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
