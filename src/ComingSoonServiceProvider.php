<?php

declare(strict_types=1);

namespace Druid\ComingSoon;

use Druid\ComingSoon\Console\Commands\InstallComingSoonCommand;
use Illuminate\Support\ServiceProvider;

class ComingSoonServiceProvider extends ServiceProvider
{
  /**
   * Bootstrap the application services.
   */
  public function boot(): void
  {
    // Register console commands
    if ($this->app->runningInConsole()) {
      $this->commands([
        InstallComingSoonCommand::class,
      ]);
    }

    // Publish resources
    $this->publishes([
      __DIR__.'/../stubs/resources/css/app.css' => resource_path('css/app.css'),
      __DIR__.'/../stubs/resources/js/pages/Welcome.vue' => resource_path('js/pages/Welcome.vue'),
    ], 'coming-soon-resources');

    // Publish storage assets
    $this->publishes([
      __DIR__.'/../stubs/storage/app/public' => storage_path('app/public'),
    ], 'coming-soon-assets');
  }

  /**
   * Register the application services.
   *
   * @return void
   */
  #[\Override]
  public function register() {}
}
