<h1>Galériák</h1>

@foreach($galleries as $gallery)
    <div style="margin-bottom: 20px;">
        <h2>
            <a href="{{ route('gallery.show', $gallery->slug) }}">
                {{ $gallery->title }}
            </a>
        </h2>

        @if($gallery->event_date)
            <div>{{ $gallery->event_date->format('Y.m.d.') }}</div>
        @endif

        <div>Képek száma: {{ $gallery->images_count }}</div>

        @if($gallery->description)
            <p>{{ $gallery->description }}</p>
        @endif
    </div>
@endforeach

{{ $galleries->links() }}