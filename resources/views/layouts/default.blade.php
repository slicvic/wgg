<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <meta name="facebook-app-id" content="{{ env('FACEBOOK_APP_ID') }}">
        <meta name="google-maps-api-key" content="{{ env('GOOGLE_MAPS_API_KEY') }}">
        <link rel="icon" href="">
        <title>Who's Got Game?!</title>
        @section('stylesheets')
            <link href="/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
            <link href="/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css" rel="stylesheet">
            <link href="/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet">
            <link href="/app/src/css/typeaheadjs.css" rel="stylesheet">
            <link href="/app/src/css/app.css" rel="stylesheet">
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
            @yield('content')
        </div>

        <script src="/bower_components/jquery/dist/jquery.min.js"></script>
        <script src="/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
        <script src="/bower_components/typeahead.js/dist/bloodhound.min.js"></script>
        <script src="/bower_components/typeahead.js/dist/typeahead.jquery.min.js"></script>
        <script src="/bower_components/vue/dist/vue.min.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places"></script>
        <script src="/app/src/js/logger.js"></script>
        <script>
            (function(d, s, id){
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) {return;}
                js = d.createElement(s); js.id = id;
                js.src = "//connect.facebook.net/en_US/sdk.js";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        </script>
        @section('javascripts')
        @show
        <script src="/app/src/js/app.js"></script>
        <script src="/app/src/js/services/facebook.js"></script>
        <script src="/app/src/js/index.js"></script>
    </body>
</html>
