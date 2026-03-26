<?php

namespace Taki47\Gallery\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Taki47\Gallery\Http\Requests\SortGalleryImagesRequest;
use Taki47\Gallery\Http\Requests\StoreGalleryImageRequest;
use Taki47\Gallery\Http\Requests\UpdateGalleryImageRequest;
use Taki47\Gallery\Models\Gallery;
use Taki47\Gallery\Models\GalleryImage;
use Taki47\Gallery\Services\ImageUploadService;

class GalleryImageAdminController extends Controller
{
    public function __construct(
        private ImageUploadService $image_upload_service
    ) {}

    public function index(Gallery $gallery)
    {
        $images = $gallery->images()->get();

        return view("gallery::admin.gallery-images.index", compact("gallery", "images"));
    }

    public function store(StoreGalleryImageRequest $request, Gallery $gallery)
    {
        $path = $this->image_upload_service->upload($request->file("image"), config("gallery.upload_path"), config("gallery.disk"));
        $sortOrder = ($gallery->images()->max("sort_order") ?? -1) + 1;

        $gallery->images()->create([
            "image_path" => $path,
            "caption" => $request->caption,
            "alt" => $request->alt,
            "sort_order" => $sortOrder
        ]);

        return redirect()
                ->back()
                ->with("success", __('gallery::gallery.messages.gallery_image_uploaded'));
    }

    public function update(UpdateGalleryImageRequest $request, Gallery $gallery, GalleryImage $image)
    {
        abort_unless($image->gallery_id === $gallery->id, 404);

        $image->update([
            "caption" => $request->caption,
            "alt" => $request->alt
        ]);

        return redirect()
                ->back()
                ->with("success", __("gallery::gallery.messages.gallery_image_updated"));
    }

    public function destroy(Gallery $gallery, GalleryImage $image)
    {
        abort_unless($image->gallery_id === $gallery->id, 404);

        $delete = $this->image_upload_service->delete($image->image_path, config("gallery.disk"));
        if ( $delete )
        {
            $image->delete();

            return redirect()
                    ->back()
                    ->with("success", __("gallery::messages.gallery_images_deleted"));
        }
    }
}