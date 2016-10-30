@if (Session::has('error'))
    <div class="alert alert-danger">
        <strong><i class="fa fa-times"></i></strong> {{ Session::get('error') }}
    </div>
@endif

@if (Session::has('success'))
    <div class="alert alert-success">
        <strong><i class="fa fa-check"></i></strong> {{ Session::get('success') }}
    </div>
@endif

@if ($errors && count($errors) && $errors instanceof \Illuminate\Support\MessageBag)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
