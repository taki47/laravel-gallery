<?php

namespace Taki47\Gallery\Console;

use Illuminate\Console\Command;

/**
 * --------------------------------------------------------------------------
 * Laravel Gallery - Install Command
 * --------------------------------------------------------------------------
 *
 * Artisan command responsible for installing the Laravel Gallery package.
 * This command publishes all required resources so the package can be used
 * and customized inside the host Laravel application.
 *
 * Published resources:
 * - configuration file
 * - blade views
 * - language files
 * - frontend/admin assets
 *
 * Command usage:
 * php artisan gallery:install
 *
 * Package: taki47/laravel-gallery
 * Author:  Lajos Takács
 * Repository: https://github.com/taki47/laravel-gallery
 */
class InstallGalleryCommand extends Command
{
    /**
     * The Artisan command signature.
     *
     * This defines how the command can be executed from the terminal.
     */
    protected $signature = 'gallery:install';

    /**
     * Description displayed in "php artisan list".
     */
    protected $description = 'Install the Laravel Gallery package';

    /**
     * Execute the console command.
     *
     * This method publishes all package resources required for the gallery
     * to function inside the host application.
     *
     * Steps performed:
     * 1. Publish configuration
     * 2. Publish Blade views
     * 3. Publish language files
     * 4. Publish frontend/admin assets
     * 5. Inform the user about running migrations
     */
    public function handle() : int
    {
        /**
         * Inform the user that installation has started.
         */
        $this->info("Installing Laravel Gallery...");

        /**
         * Publish configuration file.
         * Allows developers to customize gallery settings.
         */
        $this->call("vendor:publish", [
            "--tag" => "gallery-config",
            "--force" => true,
        ]);

        /**
         * Publish Blade views.
         * This allows overriding the package views inside the application.
         */
        $this->call("vendor:publish", [
            "--tag" => "gallery-views",
            "--force" => true
        ]);

        /**
         * Publish language files.
         * Enables translation customization by the host application.
         */
        $this->call('vendor:publish', [
            '--tag' => 'gallery-lang',
            '--force' => true,
        ]);

        /**
         * Publish CSS and JavaScript assets.
         * These assets are required for the gallery admin interface.
         */
        $this->call('vendor:publish', [
            '--tag' => 'gallery-assets',
            '--force' => true,
        ]);

        /**
         * Notify the user that installation finished successfully.
         */
        $this->info("Laravel Gallery installed successfully");

        /**
         * Inform the user about the next required step.
         * Database tables must be created via migrations.
         */
        $this->line("Next step: php artisan migrate");

        /**
         * Return successful exit code for the Artisan command.
         */
        return self::SUCCESS;
    }
}