@extends('layouts.default')
@section('body-class', 'homxe')
@section('content')
    <div class="jumbotron">
        <h1 class="display-3">Welcome to Who's Got Game!</h1>
        <p class="lead">This is a simple hero unit, a simple jumbotron-style component for calling extra attention to featured content or information.</p>
        <hr class="my-2">
        <p>It uses utility classes for typography and spacing to space content out within the larger container.</p>
        <p class="lead">
            <a class="btn btn-primary btn-lg" href="#" role="button">Learn more</a>
        </p>

        <form class="form-inline">
            <div class="form-group">
                <label class="sr-only" for="exampleInputEmail3">Email address</label>
                <input type="email" class="form-control" id="exampleInputEmail3" placeholder="Enter email">
            </div>
            <div class="form-group">
                <label class="sr-only" for="exampleInputPassword3">Password</label>
                <input type="password" class="form-control" id="exampleInputPassword3" placeholder="Password">
            </div>
            <button type="submit" class="btn btn-primary">Sign in</button>
        </form>
    </div>


    @foreach ($events as $event)
    <div class="card card-block">
        <h4 class="card-title">{{ $event->present()->title() }}</h4>
        <p class="card-text">{{ $event->id }}</p>
        <a href="#" class="card-link">Card link</a>
        <a href="#" class="card-link">Another link</a>
    </div>
    @endforeach
@endsection
