<?php

namespace Taki47\Gallery;

use Illuminate\Support\ServiceProvider;
use Taki47\Gallery\Console\InstallGalleryCommand;

/**
 * --------------------------------------------------------------------------
 * Laravel Gallery - Service Provider
 * --------------------------------------------------------------------------
 *
 * This service provider bootstraps the Laravel Gallery package.
 *
 * Responsibilities:
 * - Register package views
 * - Load package migrations
 * - Register package routes
 * - Load package translations
 * - Publish configuration, views, translations and assets
 * - Register artisan commands
 *
 * Package: taki47/laravel-gallery
 * Author:  Lajos Takács
 */
class GalleryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the package services.
     *
     * This method is responsible for loading all package resources
     * and defining publishable assets for the host application.
     */
    public function boot()
    {
        /**
         * Register package views.
         *
         * Allows usage like:
         * view('gallery::index')
         */
        $this->loadViewsFrom(__DIR__."/../resources/views", "gallery");

        /**
         * Register package migrations.
         */
        $this->loadMigrationsFrom(__DIR__."/../database/migrations");

        /**
         * Register package routes.
         */
        $this->loadRoutesFrom(__DIR__."/../routes/web.php");

        /**
         * Register package translations.
         */
        $this->loadTranslationsFrom(__DIR__."/../lang", "gallery");

        /**
         * Publish configuration file to the host application.
         */
        $this->publishes([
            __DIR__."/../config/gallery.php" => config_path("gallery.php"),
        ], "gallery-config");

        /**
         * Publish views so they can be customized by the host application.
         */
        $this->publishes([
            __DIR__."/../resources/views" => resource_path("views/vendor/gallery"),
        ], "gallery-views");

        /**
         * Publish language files for customization.
         */
        $this->publishes([
            __DIR__."/../lang" => lang_path("vendor/gallery"),
        ], "gallery-lang");

        /**
         * Publish public assets (CSS / JS).
         */
        $this->publishes([
            __DIR__."/../public" => public_path("vendor/laravel-gallery"),
        ], "gallery-assets");

        /**
         * Register artisan commands when running in console.
         */
        if ( $this->app->runningInConsole() )
            $this->commands([
                InstallGalleryCommand::class,
            ]);
    }

    /**
     * Register package services.
     *
     * Merges the package configuration with the application's config.
     */
    public function register()
    {
        /**
         * Merge default package configuration.
         */
        $this->mergeConfigFrom(
            __DIR__."/../config/gallery.php",
            "gallery"
        );
    }
}