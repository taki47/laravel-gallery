<?php

namespace Taki47\Gallery;

use Illuminate\Support\ServiceProvider;
use Taki47\Gallery\Console\InstallGalleryCommand;

class GalleryServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__."/../resources/views", "gallery");
        $this->loadMigrationsFrom(__DIR__."/../database/migrations");
        $this->loadRoutesFrom(__DIR__."/../routes/web.php");
        $this->loadTranslationsFrom(__DIR__."/../lang", "gallery");

        $this->publishes([
            __DIR__."/../config/gallery.php" => config_path("gallery.php"),
        ], "gallery-config");

        $this->publishes([
            __DIR__."/../resources/views" => resource_path("views/vendor/gallery"),
        ], "gallery-views");

        $this->publishes([
            __DIR__."/../lang" => lang_path("vendor/gallery"),
        ], "gallery-lang");

        if ( $this->app->runningInConsole() )
            $this->commands([
                InstallGalleryCommand::class,
            ]);
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__."/../config/gallery.php",
            "gallery"
        );
    }
}