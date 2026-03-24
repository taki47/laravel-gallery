<?php

return [
    "route_prefix" => env("GALLERY_ROUTE_PREFIX", "galleries"),
    "disk" => env("GALLERY_DISK", "public"),
    "upload_path" => env("GALLERY_UPLOAD_PATH", "gallery"),
    "per_page" => env("GALLERY_PER_PAGE", 12),
    "middleware" => ['web'],
];