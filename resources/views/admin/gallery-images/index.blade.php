@extends('gallery::admin.layout')

@section('content')
    <h1>{{ $gallery->title }} - {{ __("gallery::gallery.admin.manage_image") }}</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('gallery.admin.images.store', $gallery) }}" method="POST" enctype="multipart/form-data" class="mb-4">
        @csrf

        <div class="mb-3">
            <label class="form-label">{{ __("gallery::gallery.admin.image") }}</label>
            <input type="file" name="image" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">{{ __("gallery::gallery.admin.caption") }}</label>
            <input type="text" name="caption" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">{{ __("gallery::gallery.admin.alt") }}</label>
            <input type="text" name="alt" class="form-control">
        </div>

        <button type="submit" class="btn btn-success">{{ __("gallery::gallery.admin.upload_button") }}</button>
    </form>

    <div class="horizontal-line"></div>
    
    <div id="sortable-images">
        @foreach($images as $image)
            <div class="card mb-3 sortable-item" data-id="{{ $image->id }}">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-2">
                            <img src="{{ Storage::url($image->image_path) }}" class="img-fluid rounded">
                        </div>

                        <div class="col-md-8">
                            <form action="{{ route('gallery.admin.images.update', [$gallery, $image]) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="mb-2">
                                    <label class="form-label">{{ __("gallery::gallery.admin.caption") }}</label>
                                    <input type="text" name="caption" value="{{ $image->caption }}" class="form-control">
                                </div>

                                <div class="mb-2">
                                    <label class="form-label">{{ __("gallery::gallery.admin.alt") }}</label>
                                    <input type="text" name="alt" value="{{ $image->alt }}" class="form-control">
                                </div>

                                <button type="submit" class="btn btn-primary btn-sm">{{ __("gallery::gallery.admin.save_button") }}</button>
                            </form>
                        </div>

                        <div class="col-md-2 text-end">
                            <form action="{{ route('gallery.admin.images.destroy', [$gallery, $image]) }}" method="POST" onsubmit="return confirm('Biztosan törlöd?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">{{ __("gallery::gallery.admin.delete_button") }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <button id="save-order" class="btn btn-warning">{{ __("gallery::gallery.admin.order_save_button") }}</button>
@endsection