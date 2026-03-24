<?php

use Illuminate\Support\Facades\Route;
use Taki47\Gallery\Http\Controllers\GalleryController;

Route::prefix(config("gallery.route_prefix"))
    ->middleware(config("gallery.middleware"))
    ->group(function() {
        Route::get("/", [GalleryController::class, "index"])->name("gallery.index");
        Route::get("/{slug}", [GalleryController::class, "show"])->name("gallery.show");
    });