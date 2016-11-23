<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="">
        <title>Who's Got Game</title>
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
    <body class="@yield('body-class')">
        @include('layouts.default.header')

        <div id="content" class="container">
            @include('partials.flash-message')
            @yield('content')
        </div>

        @include('layouts.default.footer')
    </body>
</html>
