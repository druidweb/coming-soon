<?php

declare(strict_types=1);

namespace Druid\ComingSoon\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallComingSoonCommand extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'coming-soon:install
                            {--force : Overwrite existing files}
                            {--assets-only : Only publish assets, skip resources}
                            {--resources-only : Only publish resources, skip assets}';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Install the Coming Soon page resources and assets';

  /**
   * Execute the console command.
   */
  public function handle(): int
  {
    $this->info('Installing Coming Soon package...');

    $force = $this->option('force');
    $assetsOnly = $this->option('assets-only');
    $resourcesOnly = $this->option('resources-only');

    // Publish resources (CSS and Vue components)
    if (! $assetsOnly) {
      $this->publishResources($force);
    }

    // Publish assets (images)
    if (! $resourcesOnly) {
      $this->publishAssets($force);
    }

    $this->info('Coming Soon package installed successfully!');
    $this->newLine();
    $this->info('Next steps:');
    $this->line('1. Make sure your route points to the Welcome.vue component');
    $this->line('2. Run "npm run build" to compile your assets');
    $this->line('3. Run "php artisan storage:link" to link storage assets');

    return Command::SUCCESS;
  }

  /**
   * Publish the resource files (CSS and Vue components).
   */
  protected function publishResources(bool $force = false): void
  {
    $this->info('Publishing resources...');

    $resources = [
      'css/app.css' => [
        'source' => __DIR__.'/../../../stubs/resources/css/app.css',
        'destination' => resource_path('css/app.css'),
      ],
      'js/pages/Welcome.vue' => [
        'source' => __DIR__.'/../../../stubs/resources/js/pages/Welcome.vue',
        'destination' => resource_path('js/pages/Welcome.vue'),
      ],
    ];

    foreach ($resources as $name => $paths) {
      $this->publishFile($name, $paths['source'], $paths['destination'], $force);
    }
  }

  /**
   * Publish the asset files (images).
   */
  protected function publishAssets(bool $force = false): void
  {
    $this->info('Publishing assets...');

    $sourceDir = __DIR__.'/../../../stubs/storage/app/public';
    $destinationDir = storage_path('app/public');

    if (! File::exists($sourceDir)) {
      $this->error("Source directory does not exist: {$sourceDir}");

      return;
    }

    // Create destination directory if it doesn't exist
    if (! File::exists($destinationDir)) {
      File::makeDirectory($destinationDir, 0755, true);
    }

    // Copy all files from source to destination
    $files = File::allFiles($sourceDir);

    foreach ($files as $file) {
      $relativePath = $file->getRelativePathname();
      $destinationPath = $destinationDir.'/'.$relativePath;

      // Create subdirectories if they don't exist
      $destinationSubDir = dirname($destinationPath);
      if (! File::exists($destinationSubDir)) {
        File::makeDirectory($destinationSubDir, 0755, true);
      }

      $this->publishFile(
        "storage/{$relativePath}",
        $file->getPathname(),
        $destinationPath,
        $force
      );
    }
  }

  /**
   * Publish a single file.
   */
  protected function publishFile(string $name, string $source, string $destination, bool $force = false): void
  {
    if (! File::exists($source)) {
      $this->error("Source file does not exist: {$source}");

      return;
    }

    // Check if destination exists and force is not set
    if (File::exists($destination) && ! $force) {
      if ($this->confirm("File {$name} already exists. Overwrite?")) {
        $force = true;
      } else {
        $this->line("Skipped: {$name}");

        return;
      }
    }

    // Create destination directory if it doesn't exist
    $destinationDir = dirname($destination);
    if (! File::exists($destinationDir)) {
      File::makeDirectory($destinationDir, 0755, true);
    }

    // Copy the file
    if (File::copy($source, $destination)) {
      $this->line("Published: {$name}");
    } else {
      $this->error("Failed to publish: {$name}");
    }
  }
}
