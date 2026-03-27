{{--
|--------------------------------------------------------------------------
| Laravel Gallery - Gallery View
|--------------------------------------------------------------------------
|
| Displays images inside a selected gallery.
| Includes lightbox support.
|
--}}

@extends('gallery::layout')

@section('content')
    <div class="container py-4 py-md-5">
        <div class="mb-4">
            <a href="{{ route('gallery.index') }}" class="btn btn-outline-secondary btn-sm">
                ← {{ __("gallery::gallery.frontend.back") }}
            </a>
        </div>

        <div class="mb-4">
            <h1 class="mb-2">{{ $gallery->title }}</h1>

            @if($gallery->description)
                <p class="text-muted mb-0">{{ $gallery->description }}</p>
            @endif
        </div>

        @if($gallery->images->isEmpty())
            <p>{{ __("gallery::gallery.frontend.no_image") }}</p>
        @else
            <div class="row g-4">
                @foreach($gallery->images as $image)
                    @php
                        $imageUrl = Storage::url($image->image_path);
                        $caption = $image->caption ?: $gallery->title;
                    @endphp

                    <div class="col-12 col-sm-6 col-lg-4">
                        <div class="gallery-card">
                            <a href="{{ $imageUrl }}"
                            class="glightbox gallery-image-link"
                            data-gallery="gallery-{{ $gallery->id }}"
                            data-title="{{ e($caption) }}"
                            data-label="{{ __('gallery::gallery.frontend.actions.open_gallery') }}"
                            data-description="">
                                <img
                                    src="{{ $imageUrl }}"
                                    alt="{{ $image->alt ?: $gallery->title }}"
                                    class="gallery-image"
                                >
                            </a>

                            <div class="gallery-meta">
                                @if($image->caption)
                                    <div class="gallery-caption">{{ $image->caption }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            GLightbox({
                selector: '.glightbox',
                touchNavigation: true,
                loop: true,
                zoomable: true,
                closeButton: true
            });
        });
    </script>
@endpush