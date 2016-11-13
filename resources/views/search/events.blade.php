@extends('layouts.default.layout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <h3 class="card-header">Find</h3>
                    <div class="card-block">
                        @include('partials.events.form-search-v2', compact('input'))
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="caxrd">
                    <div class="cxard-header">
                        <h3>
                            @if ($input['keywords'])
                                "{{ $input['keywords'] }}" we found {{ $events->total() }} {{ str_plural('game', $events->total()) }}!
                            @else
                                We found {{ $events->total() }} {{ str_plural('game', $events->total()) }}!
                            @endif
                        </h3>
                        <hr>
                    </div>
                    <div class="carxd-block">
                        @forelse ($events as $event)
                            @include('partials.events.list-group-item', compact('events'))
                        @empty
                            <h3 class="lead">No games found.</h3>
                        @endforelse
                        {{ $events->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
