<?php

namespace Taki47\Gallery\Tests\Feature;

use Taki47\Gallery\Models\Gallery;
use Taki47\Gallery\Models\GalleryImage;
use Taki47\Gallery\Tests\TestCase;

class GalleryFrontendTest extends TestCase
{
    public function test_public_gallery_index_is_accessible(): void
    {
        Gallery::create([
            'title' => 'Teszt galéria',
            'slug' => 'teszt-galeria',
            'description' => 'Leírás',
            'is_public' => true,
        ]);

        $response = $this->get('/galleries');

        $response->assertOk();
        $response->assertSee('Teszt galéria');
    }

    public function test_public_gallery_show_is_accessible(): void
    {
        $gallery = Gallery::create([
            'title' => 'Esküvő',
            'slug' => 'eskuvo',
            'description' => 'Leírás',
            'is_public' => true,
        ]);

        GalleryImage::create([
            'gallery_id' => $gallery->id,
            'image_path' => 'gallery/eskuvo/kep-1.jpg',
            'caption' => 'Kép 1',
            'alt' => 'Alt 1',
            'sort_order' => 1,
        ]);

        $response = $this->get('/galleries/eskuvo');

        $response->assertOk();
        $response->assertSee('Esküvő');
        $response->assertSee('Kép 1');
    }
}