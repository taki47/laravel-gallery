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

/**
 * --------------------------------------------------------------------------
 * Laravel Gallery - Admin Gallery Controller
 * --------------------------------------------------------------------------
 *
 * Handles gallery management inside the administration interface.
 *
 * Responsibilities:
 * - List existing galleries
 * - Create new galleries
 * - Update gallery information
 * - Delete galleries and their associated images
 *
 * Package: taki47/laravel-gallery
 * Author:  Lajos Takács
 */
class GalleryAdminController extends Controller
{
    /**
     * Create controller instance.
     *
     * The ImageUploadService is injected to handle file deletion
     * when galleries or images are removed.
     */
    public function __construct(
        private ImageUploadService $image_upload_service
    )
    {}

    /**
     * Display a paginated list of galleries.
     *
     * Ordered by newest first.
     *
     * View:
     * gallery::admin.gallery.index
     */
    public function index()
    {
        /**
         * Retrieve paginated gallery list.
         */
        $galleries = Gallery::query()
                        ->latest()
                        ->paginate(config("gallery.per_page"));

        /**
         * Render the gallery list view.
         */
        return view("gallery::admin.gallery.index", compact("galleries"));
    }

    /**
     * Show the gallery creation form.
     *
     * View:
     * gallery::admin.gallery.create
     */
    public function create()
    {
        return view("gallery::admin.gallery.create");
    }

    /**
     * Store a new gallery.
     *
     * The request is validated using StoreGalleryRequest.
     * A slug is generated automatically from the gallery title.
     */
    public function store(StoreGalleryRequest $request)
    {
        /**
         * Validate incoming request data.
         */
        $validated = $request->validated();

        /**
         * Generate URL-friendly slug from title.
         */
        $slug = Str::slug($validated["title"]);

        /**
         * Create the gallery record.
         */
        Gallery::create([
            "title" => $validated["title"],
            "slug" => $slug,
            "description" => $validated["description"] ?? null,
            "is_public" => $request->boolean("is_public"),
        ]);

        /**
         * Redirect back to gallery list with success message.
         */
        return redirect()
                ->route("gallery.admin.index")
                ->with("success", __('gallery::gallery.messages.gallery.created'));
    }

    /**
     * Show the gallery edit form.
     *
     * @param Gallery $gallery
     *
     * View:
     * gallery::admin.gallery.edit
     */
    public function edit(Gallery $gallery)
    {
        return view("gallery::admin.gallery.edit", compact("gallery"));
    }

    /**
     * Update an existing gallery.
     *
     * The request is validated using UpdateGalleryRequest.
     * The slug is regenerated from the updated title.
     *
     * @param UpdateGalleryRequest $request
     * @param Gallery $gallery
     */
    public function update(UpdateGalleryRequest $request, Gallery $gallery)
    {
        /**
         * Validate incoming request data.
         */
        $validated = $request->validated();

        /**
         * Generate updated slug from title.
         */
        $slug = Str::slug($validated["title"]);

        /**
         * Update the gallery record.
         */
        $gallery->update([
            "title" => $validated["title"],
            "slug" => $slug,
            "description" => $validated["description"] ?? null,
            "is_public" => $request->boolean("is_public"),
        ]);

        /**
         * Redirect back to gallery list with success message.
         */
        return redirect()
                ->route("gallery.admin.index")
                ->with("success", __('gallery::gallery.messages.gallery.updated'));
    }

    /**
     * Delete a gallery and its associated images.
     *
     * Steps performed:
     * 1. Delete image files from storage
     * 2. Delete image database records
     * 3. Delete the gallery record
     * 4. Remove the gallery directory from the filesystem
     *
     * @param Gallery $gallery
     */
    public function destroy(Gallery $gallery)
    {
        /**
         * Prepare storage configuration.
         */
        $gallerySlug = $gallery->slug;
        $disk = config('gallery.disk');
        $directory = config('gallery.upload_path') . '/' . $gallerySlug;

        /**
         * Run database operations inside a transaction.
         */
        DB::transaction(function () use ($gallery, $disk) {

            /**
             * Delete all images belonging to the gallery.
             */
            foreach ($gallery->images as $image) {

                /**
                 * Remove image file from storage.
                 */
                $this->image_upload_service->delete(
                    $image->image_path,
                    $disk
                );

                /**
                 * Remove image database record.
                 */
                $image->delete();
            }

            /**
             * Delete the gallery record.
             */
            $gallery->delete();
        });

        /**
         * Remove gallery directory from storage.
         */
        Storage::disk($disk)->deleteDirectory($directory);

        /**
         * Redirect back to gallery list with success message.
         */
        return redirect()
                ->route("gallery.admin.index")
                ->with("success", __('gallery::gallery.messages.gallery.deleted'));
    }
}