@extends('layouts.default')

@section('content')
    <h1>Edit Event</h1>
    @include('events.partials.form', ['event' => $event])
@endsection
