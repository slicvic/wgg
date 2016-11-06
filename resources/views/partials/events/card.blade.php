<div class="card">
    <div class="card-block">
        <h4 class="card-title">{{ $event->present()->title() }} {{ $event->id }}</h4>
        <p class="card-text">
            {{ $event->present()->date('short') }}<br>
            <span class="text-warning">{{ $event->present()->time() }}</span>
        </p>
        <a href="{{ route('events.show', ['id' => $event->id])}}" class="btn btn-primary">Details</a>
    </div>
</div>
