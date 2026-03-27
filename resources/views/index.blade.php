@extends('gallery::layout')

@section('content')
    <div class="container py-4">
        <h1 class="mb-4">{{ __("gallery::gallery.frontend.title") }}</h1>

        @if($galleries->isEmpty())
            <p>{{ __("gallery::gallery.frontend.empty.galleries") }}</p>
        @else
            <div class="row">
                @foreach($galleries as $gallery)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            @php
                                $coverImage = $gallery->images()->orderBy('sort_order')->first();
                            @endphp

                            <a href="{{ route('gallery.show', $gallery->slug) }}">
                                <img
                                    src="{{ $coverImage ? Storage::url($coverImage->image_path) : asset('vendor/laravel-gallery/image/placeholder.jpg') }}"
                                    class="card-img-top"
                                    alt="{{ $coverImage->alt ?? $gallery->title }}"
                                    style="height: 250px; object-fit: cover;"
                                >
                            </a>

                            <div class="card-body d-flex flex-column">
                                <h2 class="h5">{{ $gallery->title }}</h2>

                                @if($gallery->description)
                                    <p class="text-muted">{{ $gallery->description }}</p>
                                @endif

                                <a href="{{ route('gallery.show', $gallery->slug) }}" class="btn btn-primary mt-auto">
                                    {{ __("gallery::gallery.frontend.actions.open_gallery") }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="row">
                <div class="col-12">
                    @if($galleries->hasPages())
                        <div class="mt-4 d-flex justify-content-center">
                            {{ $galleries->links() }}
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
@endsection