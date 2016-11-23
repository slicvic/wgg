@extends('layouts.default.layout')

@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('user.account.events.index') }}">Manage games</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>
    <h1 class="display-4">{{ $event->present()->title() }}</h1>
    <hr>
    @include('partials.events.form', ['event' => $event])
@endsection
