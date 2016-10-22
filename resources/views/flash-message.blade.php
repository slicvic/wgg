@if (Session::has('error'))
    <div class="alert alert-danger">
        <strong>Whoops!</strong> {{ Session::get('error') }}
    </div>
@endif
