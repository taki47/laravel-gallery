{{--
|--------------------------------------------------------------------------
| Laravel Gallery - Admin Gallery List
|--------------------------------------------------------------------------
|
| Displays the list of galleries with pagination
| and management actions.
|
--}}

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
                                <button type="button" class="btn btn-danger btn-delete">
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
        {{ $galleries->links('gallery::pagination.bootstrap') }}
    </div>

    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <p>{{ __('gallery::gallery.admin.confirm.delete_gallery') }}</p>

            <div class="modal-actions">
                <button id="confirmDelete" class="btn btn-danger">
                    {{ __('gallery::gallery.admin.buttons.delete') }}
                </button>

                <button id="cancelDelete" class="btn btn-secondary">
                    {{ __('gallery::gallery.admin.buttons.cancel') }}
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {

            const modal = document.getElementById("deleteModal");
            const confirmBtn = document.getElementById("confirmDelete");
            const cancelBtn = document.getElementById("cancelDelete");

            let currentForm = null;

            document.querySelectorAll(".btn-delete").forEach(btn => {

                btn.addEventListener("click", function () {

                    currentForm = this.closest("form");
                    modal.classList.add("active");

                });

            });

            cancelBtn.addEventListener("click", () => {
                modal.classList.remove("active");
            });

            confirmBtn.addEventListener("click", () => {

                if (currentForm) {
                    currentForm.submit();
                }

            });

        });
        </script>
@endsection