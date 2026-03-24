<?php

namespace Taki47\Gallery;

use Illuminate\Support\ServiceProvider;

class GalleryServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__."/../resources/views", "gallery");

        $this->loadMigrationFrom(__DIR__."/../databases/migrations");

        $this->publishes([
            __DIR__."/../config/gallery.php" => config_path("gallery.php"),
        ], "gallery-config.php");
    }

    public function register()
    {
        //
    }
}