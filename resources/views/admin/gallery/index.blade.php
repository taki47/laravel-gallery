@extends('gallery::admin.layout')

@section('content')
    <div class="top-bar">
        <div>
            <h1>{{ __('gallery::gallery.admin.titles.index') }}</h1>
        </div>

        <a href="{{ route('gallery.admin.create') }}" class="btn btn-primary">
            {{ __('gallery::gallery.admin.buttons.create') }}
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
                <th>{{ __('gallery::gallery.admin.fields.id') }}</th>
                <th>{{ __('gallery::gallery.admin.fields.title') }}</th>
                <th>{{ __('gallery::gallery.admin.fields.slug') }}</th>
                <th>{{ __('gallery::gallery.admin.fields.public') }}</th>
                <th>{{ __('gallery::gallery.admin.fields.created_at') }}</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($galleries as $gallery)
                <tr>
                    <td>
                        <a href="{{ route('gallery.admin.images.page', $gallery) }}" class="btn btn-sm btn-primary">
                            {{ __("gallery::gallery.admin.buttons.manage_images") }}
                        </a>
                    </td>
                    <td>{{ $gallery->id }}</td>
                    <td>{{ $gallery->title }}</td>
                    <td>{{ $gallery->slug }}</td>
                    <td>
                        {{ $gallery->is_public
                            ? __('gallery::gallery.admin.status.public_yes')
                            : __('gallery::gallery.admin.status.public_no') }}
                    </td>
                    <td>{{ $gallery->created_at?->format('Y.m.d. H:i') }}</td>
                    <td>
                        <div class="actions">
                            <a href="{{ route('gallery.admin.edit', $gallery) }}" class="btn btn-secondary">
                                {{ __('gallery::gallery.admin.buttons.edit') }}
                            </a>

                            <form
                                action="{{ route('gallery.admin.destroy', $gallery) }}"
                                method="POST"
                                class="inline-form"
                                onsubmit="return confirm('{{ __('gallery::gallery.admin.confirm.delete_gallery') }}')"
                            >
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    {{ __('gallery::gallery.admin.buttons.delete') }}
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">{{ __('gallery::gallery.admin.empty.galleries') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        {{ $galleries->links() }}
    </div>
@endsection