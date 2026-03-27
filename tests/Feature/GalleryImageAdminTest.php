<?php

namespace Taki47\Gallery\Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Taki47\Gallery\Models\Gallery;
use Taki47\Gallery\Models\GalleryImage;
use Taki47\Gallery\Tests\TestCase;

class GalleryImageAdminTest extends TestCase
{
    protected function defineEnvironment($app): void
    {
        parent::defineEnvironment($app);

        $app['config']->set('gallery.admin_middleware', ['web']);
    }

    public function test_image_can_be_uploaded(): void
    {
        Storage::fake('public');

        $gallery = Gallery::create([
            'title' => 'Teszt galéria',
            'slug' => 'teszt-galeria',
            'description' => null,
            'is_public' => true,
        ]);

        $response = $this->post("/admin/galleries/{$gallery->id}/images", [
            'image' => UploadedFile::fake()->image('kep.jpg'),
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseCount('gallery_images', 1);
    }

    public function test_image_metadata_can_be_updated(): void
    {
        $gallery = Gallery::create([
            'title' => 'Teszt',
            'slug' => 'teszt',
            'description' => null,
            'is_public' => true,
        ]);

        $image = GalleryImage::create([
            'gallery_id' => $gallery->id,
            'image_path' => 'gallery/teszt/kep.jpg',
            'caption' => '',
            'alt' => '',
            'sort_order' => 1,
        ]);

        $response = $this->patch("/admin/galleries/{$gallery->id}/images/{$image->id}", [
            'caption' => 'Új caption',
            'alt' => 'Új alt',
        ]);

        $response->assertOk();

        $this->assertDatabaseHas('gallery_images', [
            'id' => $image->id,
            'caption' => 'Új caption',
            'alt' => 'Új alt',
        ]);
    }

    public function test_images_can_be_sorted(): void
    {
        $gallery = Gallery::create([
            'title' => 'Teszt',
            'slug' => 'teszt',
            'description' => null,
            'is_public' => true,
        ]);

        $image1 = GalleryImage::create([
            'gallery_id' => $gallery->id,
            'image_path' => 'gallery/teszt/1.jpg',
            'caption' => '',
            'alt' => '',
            'sort_order' => 1,
        ]);

        $image2 = GalleryImage::create([
            'gallery_id' => $gallery->id,
            'image_path' => 'gallery/teszt/2.jpg',
            'caption' => '',
            'alt' => '',
            'sort_order' => 2,
        ]);

        $response = $this->patch("/admin/galleries/{$gallery->id}/images/sort", [
            'items' => [
                ['id' => $image1->id, 'sort_order' => 2],
                ['id' => $image2->id, 'sort_order' => 1],
            ],
        ]);

        $response->assertOk();

        $this->assertDatabaseHas('gallery_images', [
            'id' => $image1->id,
            'sort_order' => 2,
        ]);

        $this->assertDatabaseHas('gallery_images', [
            'id' => $image2->id,
            'sort_order' => 1,
        ]);
    }
}