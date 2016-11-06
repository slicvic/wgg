<a href="{{ route('events.show', ['id' => $event->id]) }}" class="list-group-item list-group-item-action">
    <h5 class="list-group-item-heading">
        {{ $event->present()->title() }}
        <span class="tag tag-default">{{ $event->type->label }}</span>
        @if ($event->isCanceled()){!! $event->present()->status(true) !!}@endif
    </h5>
    <div class="list-group-item-text">
        <div class="media">
            <div class="media-left">
                <i class="fa fa-clock-o"></i>
            </div>
            <div class="media-body">
                <div>{{ $event->present()->date('long') }}</div>
                <div class="text-warning">{{ $event->present()->time() }}</div>
            </div>
        </div>
    </div>
    <div class="list-group-item-text">
        <div class="media">
            <div class="media-left">
                <i class="fa fa-map-marker"></i>
            </div>
            <div class="media-body">
                <div><strong>{{ $event->venue->name }}</strong></div>
                <div>{{ $event->venue->address }}</div>
            </div>
        </div>
    </div>
</a>
