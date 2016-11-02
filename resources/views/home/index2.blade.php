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
        {{
            var_dump($clientGeo)
        }}

        <div class="jumbotron">
        <h1 class="display-3">Hello, world!</h1>
            <p class="lead">This is a simple hero unit, a simple jumbotron-style component for calling extra attention to featured content or information.</p>
                <hr class="my-2">
                <p>It uses utility classes for typography and spacing to space content out within the larger container.</p>
                <p class="lead">
                <a class="btn btn-primary btn-lg" href="#" role="button">Learn more</a>
            </p>
        </div>

    <div id="xmap"></div>

    <form class="form" style="position: absolute">
        <input type="hidden" name="" id="city-lat">
        <input type="hidden" name="" id="city-lng">
        <input type="text" name="" class="js-typeahead-city"
            data-bind-field-lat="#city-lat"
            data-bind-field-lng="#city-lng">

    </form>

@endsection
