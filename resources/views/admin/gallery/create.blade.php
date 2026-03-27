{{--
|--------------------------------------------------------------------------
| Laravel Gallery - Create Gallery
|--------------------------------------------------------------------------
|
| Form for creating a new gallery in the admin interface.
|
--}}

@extends('gallery::admin.layout')

@section('content')
    <link rel="stylesheet" href="{{ asset('vendor/laravel-gallery/css/gallery-admin.css') }}">
    <div class="top-bar">
        <div>
            <h1>{{ __('gallery::gallery.admin.titles.create') }}</h1>
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

    <form action="{{ route('gallery.admin.store') }}" method="POST">
        @csrf

        @include('gallery::admin.gallery._form', [
            'submitLabel' => __('gallery::gallery.admin.buttons.save')
        ])
    </form>
@endsection