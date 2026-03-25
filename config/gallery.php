<?php

return [
    "route_prefix" => env("GALLERY_ROUTE_PREFIX", "galleries"),
    
    "middleware" => ['web'],

    "admin_prefix" => env("GALLERY_ADMIN_PREFIX", "admin/galleries"),

    "admin_middleware" => ["web", "auth"],
    
    "disk" => env("GALLERY_DISK", "public"),
    
    "upload_path" => env("GALLERY_UPLOAD_PATH", "gallery"),
    
    "per_page" => env("GALLERY_PER_PAGE", 12),
    
];