<?php

namespace Taki47\Gallery\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * --------------------------------------------------------------------------
 * Laravel Gallery - Gallery Model
 * --------------------------------------------------------------------------
 *
 * Represents a gallery entity that groups multiple images.
 *
 * A gallery can:
 * - have a title and slug
 * - optionally contain a description
 * - optionally define a cover image
 * - be marked as public or private
 *
 * Relationships:
 * - images() → all images belonging to the gallery
 *
 * Package: taki47/laravel-gallery
 * Author:  Lajos Takács
 */
class Gallery extends Model
{
    /**
     * Mass assignable attributes.
     */
    protected $fillable = [
        "title",
        "slug",
        "description",
        "cover_image",
        "is_public"
    ];

    /**
     * Get all images belonging to the gallery.
     *
     * Images are automatically ordered by their sort_order field.
     */
    public function images()
    {
        return $this->hasMany(GalleryImage::class)
            ->orderBy("sort_order");
    }
}