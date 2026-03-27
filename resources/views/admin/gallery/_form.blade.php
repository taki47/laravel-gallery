<div class="form-group">
    <label class="form-label" for="title">
        {{ __('gallery::gallery.admin.fields.title') }}
    </label>
    <input
        type="text"
        id="title"
        name="title"
        value="{{ old('title', $gallery->title ?? '') }}"
        class="form-control"
        required
    >
    @error('title')
        <div class="muted" style="color:#b42318; margin-top:6px;">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label class="form-label" for="description">
        {{ __('gallery::gallery.admin.fields.description') }}
    </label>
    <textarea
        id="description"
        name="description"
        class="form-control"
    >{{ old('description', $gallery->description ?? '') }}</textarea>
    @error('description')
        <div class="muted" style="color:#b42318; margin-top:6px;">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label class="checkbox-row" for="is_public">
        <input
            type="checkbox"
            id="is_public"
            name="is_public"
            value="1"
            {{ old('is_public', $gallery->is_public ?? true) ? 'checked' : '' }}
        >
        <span>{{ __('gallery::gallery.admin.fields.public') }}</span>
    </label>
    @error('is_public')
        <div class="muted" style="color:#b42318; margin-top:6px;">{{ $message }}</div>
    @enderror
</div>

<div class="actions">
    <button type="submit" class="btn btn-primary">
        {{ $submitLabel }}
    </button>

    <a href="{{ route('gallery.admin.index') }}" class="btn">
        {{ __('gallery::gallery.admin.buttons.back') }}
    </a>
</div>