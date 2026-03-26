<?php

use Illuminate\Support\Facades\Route;
use Taki47\Gallery\Http\Controllers\Admin\GalleryAdminController;
use Taki47\Gallery\Http\Controllers\Admin\GalleryImageAdminController;
use Taki47\Gallery\Http\Controllers\GalleryController;

Route::prefix(config("gallery.route_prefix"))
    ->middleware(config("gallery.middleware"))
    ->group(function() {
        Route::get("/", [GalleryController::class, "index"])->name("gallery.index");
        Route::get("/{slug}", [GalleryController::class, "show"])->name("gallery.show");
    });

Route::prefix(config("gallery.admin_prefix"))
    ->middleware(config("gallery.admin_middleware"))
    ->group(function() {
        Route::get("/", [GalleryAdminController::class, "index"])->name("gallery.admin.index");
        Route::get("/create", [GalleryAdminController::class, "create"])->name("gallery.admin.create");
        Route::post("/store", [GalleryAdminController::class, "store"])->name("gallery.admin.store");
        Route::get("/{gallery}/edit", [GalleryAdminController::class, "edit"])->name("gallery.admin.edit");
        Route::put("/{gallery}/edit", [GalleryAdminController::class, "update"])->name("gallery.admin.update");
        Route::delete("/{gallery}", [GalleryAdminController::class, "destroy"])->name("gallery.admin.destroy");

        Route::get('{gallery}/images', [GalleryImageAdminController::class, 'index'])->name('gallery.admin.images.index');
        Route::post('{gallery}/images', [GalleryImageAdminController::class, 'store'])->name('gallery.admin.images.store');
        Route::put('{gallery}/images/{image}', [GalleryImageAdminController::class, 'update'])->name('gallery.admin.images.update');
        Route::delete('{gallery}/images/{image}', [GalleryImageAdminController::class, 'destroy'])->name('gallery.admin.images.destroy');
        Route::post('{gallery}/images/sort', [GalleryImageAdminController::class, 'sort'])->name('gallery.admin.images.sort');
    });