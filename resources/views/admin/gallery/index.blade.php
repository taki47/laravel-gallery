@extends('gallery::admin.layout')

@section('content')
    <div class="top-bar">
        <div>
            <h1>{{ __('gallery::gallery.admin.title') }}</h1>
            <div class="muted">{{ __('gallery::gallery.admin.list') }}</div>
        </div>

        <a href="{{ route('gallery.admin.create') }}" class="btn btn-primary">
            {{ __('gallery::gallery.admin.create_button') }}
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="errors-list">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th></th>
                <th>{{ __('gallery::gallery.admin.id') }}</th>
                <th>{{ __('gallery::gallery.admin.gallery_title') }}</th>
                <th>{{ __('gallery::gallery.admin.slug') }}</th>
                <th>{{ __('gallery::gallery.admin.public') }}</th>
                <th>{{ __('gallery::gallery.admin.created_at') }}</th>
                <th>{{ __('gallery::gallery.admin.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($galleries as $gallery)
                <tr>
                    <td>
                        <a href="{{ route('gallery.admin.images.index', $gallery) }}" class="btn btn-sm btn-primary">
                            Képek kezelése
                        </a>
                    </td>
                    <td>{{ $gallery->id }}</td>
                    <td>{{ $gallery->title }}</td>
                    <td>{{ $gallery->slug }}</td>
                    <td>
                        {{ $gallery->is_public
                            ? __('gallery::gallery.admin.public_yes')
                            : __('gallery::gallery.admin.public_no') }}
                    </td>
                    <td>{{ $gallery->created_at?->format('Y.m.d. H:i') }}</td>
                    <td>
                        <div class="actions">
                            <a href="{{ route('gallery.admin.edit', $gallery) }}" class="btn">
                                {{ __('gallery::gallery.admin.edit_button') }}
                            </a>

                            <form
                                action="{{ route('gallery.admin.destroy', $gallery) }}"
                                method="POST"
                                class="inline-form"
                                onsubmit="return confirm('{{ __('gallery::gallery.admin.confirm_delete') }}')"
                            >
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    {{ __('gallery::gallery.admin.delete_button') }}
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">{{ __('gallery::gallery.admin.no_items') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        {{ $galleries->links() }}
    </div>
@endsection