<?php

namespace Taki47\Gallery\Http\Controllers;

use Illuminate\Routing\Controller;
use Taki47\Gallery\Models\Gallery;

class GalleryController extends Controller
{
    public function index()
    {
        $galleries = Gallery::query()
                            ->where("is_public", true)
                            ->withCount("images")
                            ->orderByDesc("created_at")
                            ->paginate(config("gallery.per_page"));

        return view("gallery::index", compact("galleries"));
    }

    public function show(string $slug)
    {
        $gallery = Gallery::query()
                            ->where("slug", $slug)
                            ->where("is_public", true)
                            ->with(["images" => function($query) {
                                $query->orderBy("sort_order");
                            }])
                            ->firstOrFail();

        return view("gallery::show", compact("gallery"));
    }
}