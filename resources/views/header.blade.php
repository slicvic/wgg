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
                        <li><a class="dropdown-item" href="{{ route('user-profile', ['id' => Auth::user()->id]) }}"><i class="fa fa-user"></i> Profile</a></li>
                        <li><a class="dropdown-item" href="{{ route('account.events.index') }}"><i class="fa fa-futbol-o"></i> Manage games</a></li>
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
