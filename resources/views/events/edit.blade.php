@extends('layouts.default')

@section('content')
Edit
    <div v-html="events.addEditForm.validationErrors"></div>
    @include('events.partials.form', ['event' => $event])
@endsection
