<?php

namespace Taki47\Gallery\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Taki47\Gallery\Http\Requests\SortGalleryImageRequest;
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
        $translations = trans('gallery::gallery');

        return view("gallery::admin.gallery-images.index", compact("gallery", "translations"));
    }

    public function load(Gallery $gallery)
    {
        $images = $gallery
                    ->images()
                    ->orderBy("sort_order")
                    ->get()
                    ->map(function(GalleryImage $image) {
                        return [
                            'id' => $image->id,
                            'caption' => $image->caption,
                            'alt' => $image->alt,
                            'status' => $image->status ?? 'draft',
                            'sort_order' => $image->sort_order,
                            'preview_url' => Storage::url($image->image_path),
                            'update_url' => route('gallery.admin.images.update', [
                                "gallery" => $image->gallery_id,
                                "image" => $image->id
                            ]),
                            'delete_url' => route('gallery.admin.images.destroy', [
                                "gallery" => $image->gallery_id,
                                "image" => $image->id
                            ]),
                        ];
                    });

        return response()->json($images);
    }

    public function store(StoreGalleryImageRequest $request, Gallery $gallery)
    {
        $directory = config("gallery.upload_path")."/".$gallery->slug;
        $path = $this->image_upload_service->upload($request->file("image"), $directory, config("gallery.disk"));
        $sortOrder = ($gallery->images()->max("sort_order") ?? -1) + 1;

        $image = $gallery->images()->create([
            "image_path" => $path,
            "sort_order" => $sortOrder
        ]);

        return response()->json([
            'message' => __('gallery::gallery.messages.gallery_image_uploaded'),
            'data' => [
                'id' => $image->id,
                'caption' => "",
                'alt' => "",
                'sort_order' => $image->sort_order,
                'preview_url' => Storage::disk(config('gallery.disk'))->url($image->image_path),
                'update_url' => route('gallery.admin.images.update', [
                    'gallery' => $gallery->id,
                    'image' => $image->id,
                ]),
                'delete_url' => route('gallery.admin.images.destroy', [
                    'gallery' => $gallery->id,
                    'image' => $image->id,
                ]),
            ],
        ], 201);
    }

    public function update(UpdateGalleryImageRequest $request, Gallery $gallery, GalleryImage $image)
    {
        abort_unless($image->gallery_id === $gallery->id, 404);

        $image->update([
            "caption" => $request->caption,
            "alt" => $request->alt
        ]);

        return response()->json([
            "message" => __('gallery::gallery.messages.gallery_image_updated'),
        ]);
    }

    public function destroy(Gallery $gallery, GalleryImage $image)
    {
        abort_unless($image->gallery_id === $gallery->id, 404);

        $deleted = $this->image_upload_service->delete($image->image_path, config("gallery.disk"));

        if ( !$deleted )
        {
            return response()->json([
                "message" => __("gallery::gallery.messages.gallery_image_delete_error"),
            ], 500);
        }

        $image->delete();

        return response()->json([
            'message' => __('gallery::gallery.messages.gallery_image_deleted'),
        ]);
    }

    public function sort(SortGalleryImageRequest $request, Gallery $gallery)
    {
        $data = $request->validated();
        $galleryImageIds = $gallery->images()->pluck("id")->all();

        foreach ($data["items"] as $item) {
            abort_unless(in_array($item['id'], $galleryImageIds), 404);

            $gallery->images()
                ->where("id", $item["id"])
                ->update([
                    "sort_order" => $item["sort_order"],
                ]);
        }

        return response()->json([
            "message" => __('gallery::gallery.messages.gallery_image_sort_updated'),
        ]);
    }
}