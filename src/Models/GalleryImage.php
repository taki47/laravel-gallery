<?php

namespace Taki47\Gallery\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryImage extends Model
{
    protected $fillable = [
        "gallery_id",
        "image_path",
        "caption",
        "alt",
        "sort_order"
    ];

    public function gallery()
    {
        return $this->belongsTo(Gallery::class);
    }
}
