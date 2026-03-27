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

/**
 * --------------------------------------------------------------------------
 * Laravel Gallery - Admin Gallery Image Controller
 * --------------------------------------------------------------------------
 *
 * Handles image management inside the gallery administration interface.
 *
 * Responsibilities:
 * - Display the image manager page
 * - Load gallery images for the JavaScript manager
 * - Upload new images
 * - Update image metadata
 * - Delete existing images
 * - Save image sorting order
 *
 * Package: taki47/laravel-gallery
 * Author:  Lajos Takács
 */
class GalleryImageAdminController extends Controller
{
    /**
     * Create controller instance.
     *
     * The ImageUploadService is responsible for handling
     * image upload and delete operations on the filesystem.
     */
    public function __construct(
        private ImageUploadService $image_upload_service
    ) {}

    /**
     * Display the image manager page for a gallery.
     *
     * Loads the translation array required by the frontend
     * JavaScript image manager.
     *
     * View:
     * gallery::admin.gallery-images.index
     *
     * @param Gallery $gallery
     */
    public function index(Gallery $gallery)
    {
        /**
         * Load package translations for the frontend image manager.
         */
        $translations = trans('gallery::gallery');

        /**
         * Render the image manager view.
         */
        return view("gallery::admin.gallery-images.index", compact("gallery", "translations"));
    }

    /**
     * Load gallery images as JSON for the admin image manager.
     *
     * Images are ordered by sort_order and transformed into
     * a frontend-friendly structure with URLs and action endpoints.
     *
     * @param Gallery $gallery
     */
    public function load(Gallery $gallery)
    {
        /**
         * Retrieve and transform gallery images.
         */
        $images = $gallery
                    ->images()
                    ->orderBy("sort_order")
                    ->get()
                    ->map(function(GalleryImage $image) {
                        return [
                            'id' => $image->id,
                            'caption' => $image->caption,
                            'alt' => $image->alt,
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

        /**
         * Return transformed images as JSON response.
         */
        return response()->json($images);
    }

    /**
     * Store a newly uploaded gallery image.
     *
     * The uploaded file is stored inside a gallery-specific directory,
     * and a new database record is created with the next sort order value.
     *
     * @param StoreGalleryImageRequest $request
     * @param Gallery $gallery
     */
    public function store(StoreGalleryImageRequest $request, Gallery $gallery)
    {
        /**
         * Build the target upload directory based on gallery slug.
         */
        $directory = config("gallery.upload_path")."/".$gallery->slug;

        /**
         * Upload the image file using the configured disk.
         */
        $path = $this->image_upload_service->upload($request->file("image"), $directory, config("gallery.disk"));

        /**
         * Determine the next sort order value.
         */
        $sortOrder = ($gallery->images()->max("sort_order") ?? -1) + 1;

        /**
         * Create the image database record.
         */
        $image = $gallery->images()->create([
            "image_path" => $path,
            "sort_order" => $sortOrder
        ]);

        /**
         * Return the newly created image data as JSON response.
         */
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

    /**
     * Update gallery image metadata.
     *
     * Only caption and alt text are updated.
     * The image must belong to the given gallery.
     *
     * @param UpdateGalleryImageRequest $request
     * @param Gallery $gallery
     * @param GalleryImage $image
     */
    public function update(UpdateGalleryImageRequest $request, Gallery $gallery, GalleryImage $image)
    {
        /**
         * Ensure the image belongs to the selected gallery.
         */
        abort_unless($image->gallery_id === $gallery->id, 404);

        /**
         * Update image metadata.
         */
        $image->update([
            "caption" => $request->caption,
            "alt" => $request->alt
        ]);

        /**
         * Return success response.
         */
        return response()->json([
            "message" => __('gallery::gallery.messages.image.updated'),
        ]);
    }

    /**
     * Delete a gallery image.
     *
     * The image must belong to the given gallery.
     * First the file is removed from storage, then the database record.
     *
     * @param Gallery $gallery
     * @param GalleryImage $image
     */
    public function destroy(Gallery $gallery, GalleryImage $image)
    {
        /**
         * Ensure the image belongs to the selected gallery.
         */
        abort_unless($image->gallery_id === $gallery->id, 404);

        /**
         * Delete the image file from storage.
         */
        $deleted = $this->image_upload_service->delete($image->image_path, config("gallery.disk"));

        /**
         * Return error response if file deletion failed.
         */
        if ( !$deleted )
        {
            return response()->json([
                "message" => __("gallery::gallery.messages.gallery_image_delete_error"),
            ], 500);
        }

        /**
         * Delete the image database record.
         */
        $image->delete();

        /**
         * Return success response.
         */
        return response()->json([
            'message' => __('gallery::gallery.messages.image.deleted'),
        ]);
    }

    /**
     * Update image sorting order.
     *
     * Validates the incoming sort payload and ensures that
     * only images belonging to the selected gallery are updated.
     *
     * @param SortGalleryImageRequest $request
     * @param Gallery $gallery
     */
    public function sort(SortGalleryImageRequest $request, Gallery $gallery)
    {
        /**
         * Validate incoming request data.
         */
        $data = $request->validated();

        /**
         * Collect valid image IDs for the selected gallery.
         */
        $galleryImageIds = $gallery->images()->pluck("id")->all();

        /**
         * Update sort order for each submitted image item.
         */
        foreach ($data["items"] as $item) {
            abort_unless(in_array($item['id'], $galleryImageIds), 404);

            $gallery->images()
                ->where("id", $item["id"])
                ->update([
                    "sort_order" => $item["sort_order"],
                ]);
        }

        /**
         * Return success response.
         */
        return response()->json([
            "message" => __('gallery::gallery.messages.sort.updated'),
        ]);
    }
}