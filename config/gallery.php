<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Frontend route prefix
    |--------------------------------------------------------------------------
    |
    | This prefix will be used for public gallery routes.
    | Example: https://example.com/galleries
    |
    */
    "route_prefix" => env("GALLERY_ROUTE_PREFIX", "galleries"),


    /*
    |--------------------------------------------------------------------------
    | Frontend middleware
    |--------------------------------------------------------------------------
    |
    | Middleware applied to public gallery routes.
    |
    */
    "middleware" => ['web'],


    /*
    |--------------------------------------------------------------------------
    | Admin route prefix
    |--------------------------------------------------------------------------
    |
    | Prefix for the gallery admin panel routes.
    | Example: https://example.com/admin/galleries
    |
    */
    "admin_prefix" => env("GALLERY_ADMIN_PREFIX", "admin/galleries"),


    /*
    |--------------------------------------------------------------------------
    | Admin middleware
    |--------------------------------------------------------------------------
    |
    | Middleware stack protecting the admin routes.
    | Typically includes authentication.
    |
    */
    "admin_middleware" => ["web", "auth"],


    /*
    |--------------------------------------------------------------------------
    | Storage disk
    |--------------------------------------------------------------------------
    |
    | Filesystem disk used for storing gallery images.
    | Must exist in config/filesystems.php.
    |
    */
    "disk" => env("GALLERY_DISK", "public"),


    /*
    |--------------------------------------------------------------------------
    | Upload path
    |--------------------------------------------------------------------------
    |
    | Base folder inside the disk where gallery images will be stored.
    |
    */
    "upload_path" => env("GALLERY_UPLOAD_PATH", "gallery"),


    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    |
    | Number of galleries displayed per page on the frontend.
    |
    */
    "per_page" => env("GALLERY_PER_PAGE", 12),

];