@extends('layouts.default')
@section('body-class', 'home')
@section('content')

<style>
          /* Always set the map height explicitly to define the size of the div
           * element that contains the map. */
          #map {
              height: 100%;
              width: 100%;
          }
          #content {
              height: 100%;
              width: 100%;
              padding: 0;
              margin: 0;
          }
          /* Optional: Makes the sample page fill the window. */
          html, body {
            height: 100%;
            width: 100%;

          }
        </style>
    <div id="map"></div>

    <form class="form" style="position: absolute">
        <input type="hidden" name="" id="city-lat">
        <input type="hidden" name="" id="city-lng">
        <input type="text" name="" class="js-typeahead-city"
            data-bind-field-lat="#city-lat"
            data-bind-field-lng="#city-lng">

    </form>

@endsection
