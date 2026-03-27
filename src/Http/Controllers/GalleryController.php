<?php

namespace Taki47\Gallery\Http\Controllers;

use Illuminate\Routing\Controller;
use Taki47\Gallery\Models\Gallery;

/**
 * --------------------------------------------------------------------------
 * Laravel Gallery - Frontend Gallery Controller
 * --------------------------------------------------------------------------
 *
 * Handles the public gallery pages.
 *
 * Responsibilities:
 * - Display the list of public galleries
 * - Display a single gallery with its images
 *
 * This controller is used by the frontend routes of the package.
 *
 * Package: taki47/laravel-gallery
 * Author:  Lajos Takács
 */
class GalleryController extends Controller
{
    /**
     * Display the list of public galleries.
     *
     * Only galleries marked as public are returned.
     * The result includes the number of images in each gallery
     * and is ordered by creation date (newest first).
     *
     * Pagination size is configurable via:
     * config('gallery.per_page')
     *
     * View:
     * gallery::index
     */
    public function index()
    {
        /**
         * Query public galleries with image count.
         */
        $galleries = Gallery::query()
                            ->where("is_public", true)
                            ->withCount("images")
                            ->orderByDesc("created_at")
                            ->paginate(config("gallery.per_page"));

        /**
         * Render the gallery list view.
         */
        return view("gallery::index", compact("galleries"));
    }

    /**
     * Display a single gallery.
     *
     * The gallery is retrieved by its slug and must be public.
     * Associated images are loaded and ordered by their sort order.
     *
     * If no matching gallery is found, a 404 response is returned.
     *
     * View:
     * gallery::show
     *
     * @param string $slug
     */
    public function show(string $slug)
    {
        /**
         * Retrieve the gallery with ordered images.
         */
        $gallery = Gallery::query()
                            ->where("slug", $slug)
                            ->where("is_public", true)
                            ->with(["images" => function($query) {
                                $query->orderBy("sort_order");
                            }])
                            ->firstOrFail();

        /**
         * Render the gallery detail view.
         */
        return view("gallery::show", compact("gallery"));
    }
}