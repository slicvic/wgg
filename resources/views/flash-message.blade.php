@if (Session::has('error'))
    <div class="alert alert-danger">
        <strong><i class="fa fa-times"></i> Whoops!</strong> {{ Session::get('error') }}
    </div>
@endif

@if (Session::has('success'))
    <div class="alert alert-success">
        <strong><i class="fa fa-check"></i></strong> {{ Session::get('success') }}
    </div>
@endif

@if ($errors && $errors instanceof \Illuminate\Support\MessageBag && count($errors) > 0)
    <div class="alert alert-danger">
        <strong><i class="fa fa-times"></i> Whoops</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
