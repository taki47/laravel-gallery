<?php

namespace Taki47\Gallery\Console;

use Illuminate\Console\Command;

class InstallGalleryCommand extends Command
{
    protected $signature = 'gallery:install';
    protected $description = 'Install the Laravel Gallery package';

    /**
     * Execute the console command.
     */
    public function handle() : int
    {
        $this->info("Installing Laravel Gallery...");

        $this->call("vendor:publish", [
            "--tag" => "gallery-config",
            "--force" => true,
        ]);

        $this->call("vendor:publish", [
            "--tag" => "gallery-views",
            "--force" => true
        ]);

        $this->call('vendor:publish', [
            '--tag' => 'gallery-lang',
            '--force' => true,
        ]);

        $this->info("Laravel Gallery installed successfully");
        $this->line("Next step: php artisan migrate");

        return self::SUCCESS;
    }
}
