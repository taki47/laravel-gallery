<?php

namespace Taki47\Gallery\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $fillable = [
        "title",
        "slug",
        "description",
        "cover_image",
        "is_public"
    ];

    public function images()
    {
        return $this->hasMany(GalleryImage::class)
            ->orderBy("sort_order");
    }
}
