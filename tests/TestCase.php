<?php

namespace Taki47\Gallery\Tests;

use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            GalleryServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('app.key', 'base64:'.base64_encode(str_repeat('a', 32)));
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['config']->set('gallery.disk', 'public');
        $app['config']->set('filesystems.default', 'public');
        $app['config']->set('filesystems.disks.public', [
            'driver' => 'local',
            'root' => __DIR__.'/../workbench/storage/app/public',
            'url' => '/storage',
            'visibility' => 'public',
        ]);
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
