@extends('layouts.default')

@section('content')
<form class="form">
    <input type="hidden" name="" id="city-lat">
    <input type="hidden" name="" id="city-lng">

    <input type="text" name="" class="js-typeahead-city"
    data-bind-field-lat="#city-lat"
    data-bind-field-lng="#city-lng"
    >
    <div id="sss"></div>

</form>
@endsection
