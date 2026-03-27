# Laravel Gallery

Reusable **image gallery package for Laravel** with an admin interface,
image upload, drag & drop sorting and a simple frontend gallery.

------------------------------------------------------------------------

## Features

-   Gallery CRUD (admin)
-   Image upload with drag & drop
-   Caption and alt text editing
-   Image sorting (drag & drop)
-   Public gallery frontend
-   Configurable routes and pagination
-   Publishable views, config, translations and assets
-   Works with **Laravel 10, 11, 12 and 13**

------------------------------------------------------------------------

# Installation

Install the package via Composer:

    composer require taki47/laravel-gallery

Publish package files:

    php artisan gallery:install

Run migrations:

    php artisan migrate

------------------------------------------------------------------------

# Configuration

The package can be configured either through the published config file
**or via environment variables**.

## Environment variables

You may define the following variables in your `.env` file:

    GALLERY_ROUTE_PREFIX=galleries
    GALLERY_ADMIN_PREFIX=admin/galleries
    GALLERY_DISK=public
    GALLERY_UPLOAD_PATH=gallery
    GALLERY_PER_PAGE=12

## Config file

If you prefer, you can publish and modify the configuration file:

    php artisan vendor:publish --tag=gallery-config

This will create:

    config/gallery.php

Example configuration:

    return [

        "route_prefix" => env("GALLERY_ROUTE_PREFIX", "galleries"),

        "middleware" => ['web'],

        "admin_prefix" => env("GALLERY_ADMIN_PREFIX", "admin/galleries"),

        "admin_middleware" => ["web", "auth"],

        "disk" => env("GALLERY_DISK", "public"),

        "upload_path" => env("GALLERY_UPLOAD_PATH", "gallery"),

        "per_page" => env("GALLERY_PER_PAGE", 12),

    ];

If no configuration is published, the package will use the default
values.

------------------------------------------------------------------------

# Usage

## Frontend gallery list

    /galleries

Displays all public galleries.

## Single gallery

    /galleries/{slug}

Displays a gallery with all images.

------------------------------------------------------------------------

# Admin Panel

Admin routes:

    /admin/galleries

Features:

-   create galleries
-   edit galleries
-   delete galleries
-   upload images
-   edit captions
-   reorder images

Image manager includes:

-   drag & drop sorting
-   caption editing
-   alt text editing
-   instant save

------------------------------------------------------------------------

# Image Upload

Images are uploaded into:

    storage/app/public/gallery/{gallery-slug}

Filenames are automatically:

-   slugified
-   made unique if duplicates exist

Example:

    my-photo.jpg
    my-photo-1.jpg
    my-photo-2.jpg

------------------------------------------------------------------------

# Customization

You can publish package resources:

## Config

    php artisan vendor:publish --tag=gallery-config

## Views

    php artisan vendor:publish --tag=gallery-views

## Translations

    php artisan vendor:publish --tag=gallery-lang

## Assets

    php artisan vendor:publish --tag=gallery-assets

------------------------------------------------------------------------

# Requirements

-   PHP **8.2+**
-   Laravel **10+**

------------------------------------------------------------------------

# License

MIT License

------------------------------------------------------------------------

# Author

**Lajos Takács**\
https://takiwebneked.hu
