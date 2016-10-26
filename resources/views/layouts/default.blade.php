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
            <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-AysaV+vQoT3kOAXZkl02PThvDr8HYKPZhNT5h/CXfBThSRXQ6jW5DO2ekP5ViFdi" crossorigin="anonymous">
            <link href="/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css" rel="stylesheet">
            <link href="/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet">
            <link href="/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
            <link href="/bower_components/toastr/toastr.min.css" rel="stylesheet">
            <link href="/app/css/typeaheadjs.css" rel="stylesheet">
            <link href="/app/css/app.css" rel="stylesheet">
        @show
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="@yield('body-class', '')">
        @section('header')
            <nav id="nav" class="navbar navbar-fixed-top navbar-dark bg-inverse">
                <a class="navbar-brand" href="{{ route('home') }}">WGG</a>
                <ul class="nav navbar-nav">
                    <li class="nav-item">
                        <a class="btn btn-danger" href="#" v-on:click="createEvent">Create a Game</a>
                    </li>
                </ul>
                <ul class="nav navbar-nav pull-right">
                    @if (Auth::check())
                        <li class="nav-item dropdown pull-right">
                            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                                <img class="rounded" src="{{ Auth::user()->present()->profilePictureUrl(20, 20) }}"> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('account.events.index') }}"><i class="fa fa-futbol-o"></i> Manage Games</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a class="dropdown-item" href="{{ route('logout') }}"><i class="fa fa-sign-out"></i> Logout</a></li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a href="#" class="nav-link" v-on:click="login">Login</a>
                        </li>
                    @endif
                </ul>
            </nav>
        @show

        <div id="content" class="container">
            @include('flash-message')
            @yield('content')
        </div>

        <script src="/bower_components/jquery/dist/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/js/bootstrap.min.js" integrity="sha384-BLiI7JTZm+JWlgKa0M0kGRpJbF2J8q+qreVrKBC47e3K6BW78kGLrCkeRX6I9RoK" crossorigin="anonymous"></script>
        <script type="text/javascript" src="/bower_components/moment/min/moment.min.js"></script>
        <script src="/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
        <script type="text/javascript" src="/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
        <script src="/bower_components/typeahead.js/dist/bloodhound.min.js"></script>
        <script src="/bower_components/typeahead.js/dist/typeahead.jquery.min.js"></script>
        <script src="/bower_components/vue/dist/vue.min.js"></script>
        <script src="/bower_components/parsleyjs/dist/parsley.min.js"></script>
        <script src="/bower_components/toastr/toastr.min.js"></script>
        <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places"></script>
        <script type="text/javascript">
            var globalSettings = {
                csrfToken: '{{ csrf_token() }}',
                user: {
                    isLoggedIn: {{ Auth::check() ? 'true' : 'false' }}
                },
                routes: {
                    login: {
                        facebook: '{{ route('login.facebook') }}'
                    },
                    account: {
                        events: '{{ route('account.events.index') }}'
                    },
                    events: {
                        create: '{{ route('events.create') }}'
                    }
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
            };
        </script>
        <script src="/app/js/utils/logger.js"></script>
        <script src="/app/js/services/facebook.js"></script>
        <script src="/app/js/services/googlemaps.js"></script>
        <script src="/app/js/app.js"></script>
    </body>
</html>
