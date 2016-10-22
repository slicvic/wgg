<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="">
        <title>Who's Got Game?!</title>
        @section('stylesheets')
            <link href="/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
            <link href="/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css" rel="stylesheet">
            <link href="/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet">
            <link href="/app/css/typeaheadjs.css" rel="stylesheet">
            <link href="/app/css/app.css" rel="stylesheet">
        @show
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        @section('header')
            <nav class="navbar navbar-inverse navbar-fixed-top">
                <div class="container">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="#">Travel App</a>
                    </div>
                    <div id="navbar" class="collapse navbar-collapse">
                        <ul class="nav navbar-nav">
                            <li class="active"><a href="{{ URL::route('home') }}">Home</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
        @show
        <div class="container">
            @include('flash-message')
            @yield('content')
        </div>

        <script src="/bower_components/jquery/dist/jquery.min.js"></script>
        <script src="/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
        <script src="/bower_components/typeahead.js/dist/bloodhound.min.js"></script>
        <script src="/bower_components/typeahead.js/dist/typeahead.jquery.min.js"></script>
        <script src="/bower_components/vue/dist/vue.min.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places"></script>
        <script src="/app/js/logger.js"></script>
        <script type="text/javascript">
            var globalSettings = {
                application: {
                    routes: {
                        login: '{{ route('login') }}'
                    },
                    facebook: {
                        appId: {{ env('FACEBOOK_APP_ID') }}
                    },
                    google: {
                        maps: {
                            apiKey: '{{ env('GOOGLE_MAPS_API_KEY') }}',
                            services: {
                                autocomplete: new google.maps.places.AutocompleteService(),
                                places: new google.maps.places.PlacesService(document.createElement('span')),
                            }
                        }
                    }
                }
            };
        </script>
        @section('javascripts')
        @show
        <script src="/app/js/app.js"></script>
        <script src="/app/js/services/facebook.js"></script>
        <script src="/app/js/index.js"></script>
    </body>
</html>
