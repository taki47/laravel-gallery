<h1>{{ $gallery->title }}</h1>

@if($gallery->event_date)
    <div>{{ $gallery->event_date->format('Y.m.d.') }}</div>
@endif

@if($gallery->description)
    <p>{{ $gallery->description }}</p>
@endif

@foreach($gallery->images as $image)
    <div style="margin-bottom: 20px;">
        <img src="{{ \Illuminate\Support\Facades\Storage::disk(config('gallery.disk', 'public'))->url($image->image_path) }}"
             alt="{{ $image->alt ?? $image->caption ?? $gallery->title }}"
             style="max-width: 300px; height: auto;">

        @if($image->caption)
            <div>{{ $image->caption }}</div>
        @endif
    </div>
@endforeach