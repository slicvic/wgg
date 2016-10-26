@extends('layouts.default')

@section('content')
    <h1>Create a Game</h1>
    <hr>
    @include('events.partials.form', ['event' => $event])
@endsection
