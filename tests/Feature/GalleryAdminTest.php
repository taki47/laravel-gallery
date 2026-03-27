<?php

namespace Taki47\Gallery\Tests\Feature;

use Taki47\Gallery\Models\Gallery;
use Taki47\Gallery\Tests\TestCase;

class GalleryAdminTest extends TestCase
{
    protected function defineEnvironment($app): void
    {
        parent::defineEnvironment($app);

        $app['config']->set('gallery.admin_middleware', ['web']);
    }

    public function test_admin_gallery_store_creates_gallery(): void
    {
        $response = $this->post('/admin/galleries', [
            'title' => 'Új galéria',
            'description' => 'Teszt leírás',
            'is_public' => 1,
        ]);

        $response->assertRedirect('/admin/galleries');

        $this->assertDatabaseHas('galleries', [
            'title' => 'Új galéria',
            'slug' => 'uj-galeria',
            'is_public' => 1,
        ]);
    }

    public function test_admin_gallery_update_updates_gallery(): void
    {
        $gallery = Gallery::create([
            'title' => 'Régi cím',
            'slug' => 'regi-cim',
            'description' => 'Régi',
            'is_public' => true,
        ]);

        $response = $this->put("/admin/galleries/{$gallery->id}", [
            'title' => 'Új cím',
            'description' => 'Új leírás',
            'is_public' => 0,
        ]);

        $response->assertRedirect('/admin/galleries');

        $this->assertDatabaseHas('galleries', [
            'id' => $gallery->id,
            'title' => 'Új cím',
            'slug' => 'uj-cim',
            'is_public' => 0,
        ]);
    }
}