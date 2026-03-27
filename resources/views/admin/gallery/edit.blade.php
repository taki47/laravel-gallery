{{--
|--------------------------------------------------------------------------
| Laravel Gallery - Edit Gallery
|--------------------------------------------------------------------------
|
| Form for editing an existing gallery.
|
--}}

@extends('gallery::admin.layout')

@section('content')
    <div class="top-bar">
        <div>
            <h1>{{ __('gallery::gallery.admin.titles.edit') }}</h1>
            <div class="muted">{{ $gallery->title }}</div>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="errors-list">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('gallery.admin.update', $gallery) }}" method="POST">
        @csrf
        @method('PUT')

        @include('gallery::admin.gallery._form', [
            'gallery' => $gallery,
            'submitLabel' => __('gallery::gallery.admin.buttons.update')
        ])
    </form>
@endsection