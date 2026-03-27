<?php

namespace Taki47\Gallery\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * --------------------------------------------------------------------------
 * Laravel Gallery - Gallery Image Model
 * --------------------------------------------------------------------------
 *
 * Represents an image belonging to a gallery.
 *
 * Each image:
 * - belongs to a gallery
 * - stores the file path of the uploaded image
 * - can contain optional caption and alt text
 * - has a sortable position within the gallery
 *
 * Relationships:
 * - gallery() → the parent gallery
 *
 * Package: taki47/laravel-gallery
 * Author:  Lajos Takács
 */
class GalleryImage extends Model
{
    /**
     * Mass assignable attributes.
     */
    protected $fillable = [
        "gallery_id",
        "image_path",
        "caption",
        "alt",
        "sort_order"
    ];

    /**
     * Get the gallery that owns the image.
     */
    public function gallery()
    {
        return $this->belongsTo(Gallery::class);
    }
}