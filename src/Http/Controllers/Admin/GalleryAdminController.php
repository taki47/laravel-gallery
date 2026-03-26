<?php

namespace Taki47\Gallery\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Taki47\Gallery\Http\Requests\StoreGalleryRequest;
use Taki47\Gallery\Http\Requests\UpdateGalleryRequest;
use Taki47\Gallery\Models\Gallery;
use Taki47\Gallery\Services\ImageUploadService;

class GalleryAdminController extends Controller
{
    public function __construct(
        private ImageUploadService $image_upload_service
    )
    {}

    public function index()
    {
        $galleries = Gallery::query()
                        ->latest()
                        ->paginate(config("gallery.per_page"));

        return view("gallery::admin.gallery.index", compact("galleries"));
    }

    public function create()
    {
        return view("gallery::admin.gallery.create");
    }

    public function store(StoreGalleryRequest $request)
    {
        $validated = $request->validated();
        $slug = Str::slug($validated["title"]);

        Gallery::create([
            "title" => $validated["title"],
            "slug" => $slug,
            "description" => $validated["description"] ?? null,
            "is_public" => $request->boolean("is_public"),
        ]);

        return redirect()
                ->route("gallery.admin.index")
                ->with("success", __('gallery::gallery.messages.gallery_created'));
    }

    public function edit(Gallery $gallery)
    {
        return view("gallery::admin.gallery.edit", compact("gallery"));
    }

    public function update(UpdateGalleryRequest $request, Gallery $gallery)
    {
        $validated = $request->validated();
        $slug = Str::slug($validated["title"]);

        $gallery->update([
            "title" => $validated["title"],
            "slug" => $slug,
            "description" => $validated["description"] ?? null,
            "is_public" => $request->boolean("is_public"),
        ]);

        return redirect()
                ->route("gallery.admin.index")
                ->with("success", __('gallery::gallery.messages.gallery_updated'));
    }

    public function destroy(Gallery $gallery)
    {
        $gallerySlug = $gallery->slug;
        $disk = config('gallery.disk');
        $directory = config('gallery.upload_path') . '/' . $gallerySlug;

        DB::transaction(function () use ($gallery, $disk) {
            foreach ($gallery->images as $image) {
                $this->image_upload_service->delete(
                    $image->image_path,
                    $disk
                );

                $image->delete();
            }

            $gallery->delete();
        });

        Storage::disk($disk)->deleteDirectory($directory);

        return redirect()
                ->route("gallery.admin.index")
                ->with("success", __('gallery::gallery.messages.gallery_deleted'));
    }
}