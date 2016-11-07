<div class="card">
    <div class="card-block">
        <h5 class="card-title">{{ $event->present()->title() }} {{ $event->id }}</h5>
        <p class="card-text">
            <span class="text-muted">{{ $event->present()->date('short') }}</span> <br>
            {{ $event->present()->time() }} <br>
            {{ $event->venue->name }}
        </p>
        <a href="{{ route('events.show', ['id' => $event->id])}}" class="btn btn-primary">Details</a>
    </div>
</div>
