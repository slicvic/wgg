@extends('layouts.default')

@section('content')
    <h1>Create Event</h1>
    @include('events.partials.form', ['event' => $event])
@endsection
