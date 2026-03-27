{{--
|--------------------------------------------------------------------------
| Laravel Gallery - Image Manager
|--------------------------------------------------------------------------
|
| Vue powered interface for managing gallery images.
| Supports upload, sorting and metadata editing.
|
--}}

@extends('gallery::admin.layout')

@section('content')
    <script>
        window.GalleryLang = @json($translations);
    </script>
    <div id="laravel-gallery-manager"
        data-gallery-id="{{ $gallery->id }}"
        data-load-url="{{ route('gallery.admin.images.load', $gallery->id) }}"
        data-store-url="{{ route('gallery.admin.images.store', $gallery->id) }}"
        data-sort-url="{{ route('gallery.admin.images.sort', $gallery->id) }}"
        data-back-url="{{ route('gallery.admin.index') }}"
        data-csrf="{{ csrf_token() }}">
    </div>

    <link rel="stylesheet" href="https://releases.transloadit.com/uppy/v3.25.2/uppy.min.css">

    <script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>
    <script src="https://releases.transloadit.com/uppy/v3.25.2/uppy.min.js"></script>
    <script src="{{ asset('vendor/laravel-gallery/js/gallery-manager.js') }}"></script>
@endsection