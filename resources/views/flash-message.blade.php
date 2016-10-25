@if (Session::has('error'))
    <div class="alert alert-danger">
        <strong>Whoops!</strong> {{ Session::get('error') }}
    </div>
@endif

@if (Session::has('success'))
    <div class="alert alert-success">
        <strong>Woohoo!</strong> {{ Session::get('success') }}
    </div>
@endif

@if ($errors && $errors instanceof \Illuminate\Support\MessageBag && count($errors) > 0)
    <div class="alert alert-danger">
        <b>Whoops! There were some errors!</b>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
