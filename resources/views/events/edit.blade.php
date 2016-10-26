@extends('layouts.default')

@section('content')
    <h1>Edit Game <small class="text-muted">{{ $event->present()->title() }}</small></h1>
    <hr>
    @include('events.partials.form', ['event' => $event])
@endsection
