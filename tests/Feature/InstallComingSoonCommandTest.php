<?php

declare(strict_types=1);

use Druid\ComingSoon\Console\Commands\InstallComingSoonCommand;
use Illuminate\Support\Facades\File;

beforeEach(function (): void {
  // Clean up any test files
  cleanupTestFiles();
});

afterEach(function (): void {
  // Clean up any test files
  cleanupTestFiles();
});

it('can install coming soon resources successfully', function (): void {
  // Ensure directories don't exist initially
  expect(File::exists(resource_path('css/app.css')))->toBeFalse();
  expect(File::exists(resource_path('js/pages/Welcome.vue')))->toBeFalse();

  // Run the install command
  $this->artisan(InstallComingSoonCommand::class)
    ->expectsOutput('Installing Coming Soon package...')
    ->expectsOutput('Publishing resources...')
    ->expectsOutput('Publishing assets...')
    ->expectsOutput('Coming Soon package installed successfully!')
    ->assertExitCode(0);

  // Verify files were created
  expect(File::exists(resource_path('css/app.css')))->toBeTrue();
  expect(File::exists(resource_path('js/pages/Welcome.vue')))->toBeTrue();
  expect(File::exists(storage_path('app/public/img/bgtile.png')))->toBeTrue();
  expect(File::exists(storage_path('app/public/img/logo.svg')))->toBeTrue();
});

it('can install only resources when assets-only flag is used', function (): void {
  $this->artisan(InstallComingSoonCommand::class, ['--resources-only' => true])
    ->expectsOutput('Installing Coming Soon package...')
    ->expectsOutput('Publishing resources...')
    ->doesntExpectOutput('Publishing assets...')
    ->assertExitCode(0);

  // Verify only resources were created
  expect(File::exists(resource_path('css/app.css')))->toBeTrue();
  expect(File::exists(resource_path('js/pages/Welcome.vue')))->toBeTrue();
  expect(File::exists(storage_path('app/public/img/bgtile.png')))->toBeFalse();
});

it('can install only assets when assets-only flag is used', function (): void {
  $this->artisan(InstallComingSoonCommand::class, ['--assets-only' => true])
    ->expectsOutput('Installing Coming Soon package...')
    ->expectsOutput('Publishing assets...')
    ->doesntExpectOutput('Publishing resources...')
    ->assertExitCode(0);

  // Verify only assets were created
  expect(File::exists(storage_path('app/public/img/bgtile.png')))->toBeTrue();
  expect(File::exists(storage_path('app/public/img/logo.svg')))->toBeTrue();
  expect(File::exists(resource_path('css/app.css')))->toBeFalse();
});

it('prompts for confirmation when files already exist', function (): void {
  // Create existing files
  File::ensureDirectoryExists(resource_path('css'));
  File::ensureDirectoryExists(resource_path('js/pages'));
  File::put(resource_path('css/app.css'), 'existing content');
  File::put(resource_path('js/pages/Welcome.vue'), 'existing content');

  $this->artisan(InstallComingSoonCommand::class)
    ->expectsQuestion('File css/app.css already exists. Overwrite?', false)
    ->expectsQuestion('File js/pages/Welcome.vue already exists. Overwrite?', false)
    ->expectsOutput('Skipped: css/app.css')
    ->expectsOutput('Skipped: js/pages/Welcome.vue')
    ->assertExitCode(0);

  // Verify files were not overwritten
  expect(File::get(resource_path('css/app.css')))->toBe('existing content');
  expect(File::get(resource_path('js/pages/Welcome.vue')))->toBe('existing content');
});

it('overwrites files when force flag is used', function (): void {
  // Create existing files
  File::ensureDirectoryExists(resource_path('css'));
  File::ensureDirectoryExists(resource_path('js/pages'));
  File::put(resource_path('css/app.css'), 'existing content');
  File::put(resource_path('js/pages/Welcome.vue'), 'existing content');

  $this->artisan(InstallComingSoonCommand::class, ['--force' => true])
    ->expectsOutput('Published: css/app.css')
    ->expectsOutput('Published: js/pages/Welcome.vue')
    ->assertExitCode(0);

  // Verify files were overwritten
  expect(File::get(resource_path('css/app.css')))->not()->toBe('existing content');
  expect(File::get(resource_path('js/pages/Welcome.vue')))->not()->toBe('existing content');
});

it('creates directories if they do not exist', function (): void {
  // Ensure directories don't exist
  if (File::exists(resource_path('css'))) {
    File::deleteDirectory(resource_path('css'));
  }
  if (File::exists(resource_path('js'))) {
    File::deleteDirectory(resource_path('js'));
  }

  $this->artisan(InstallComingSoonCommand::class)
    ->assertExitCode(0);

  // Verify directories were created
  expect(File::exists(resource_path('css')))->toBeTrue();
  expect(File::exists(resource_path('js/pages')))->toBeTrue();
  expect(File::exists(storage_path('app/public/img')))->toBeTrue();
});

it('handles missing source directory gracefully', function (): void {
  // Mock the source directory to not exist by temporarily moving it
  $originalStubsDir = __DIR__.'/../../stubs';
  $tempStubsDir = __DIR__.'/../../stubs_temp';

  if (File::exists($originalStubsDir)) {
    File::move($originalStubsDir, $tempStubsDir);
  }

  $this->artisan(InstallComingSoonCommand::class, ['--assets-only' => true])
    ->expectsOutputToContain('Source directory does not exist')
    ->assertExitCode(0);

  // Restore the stubs directory
  if (File::exists($tempStubsDir)) {
    File::move($tempStubsDir, $originalStubsDir);
  }
});

it('handles missing source files gracefully', function (): void {
  // Create a temporary stub file that doesn't exist
  $tempStubsDir = __DIR__.'/../../stubs_temp';
  File::ensureDirectoryExists($tempStubsDir.'/resources/css');
  File::ensureDirectoryExists($tempStubsDir.'/resources/js/pages');

  // Create empty files to trigger the missing source file error
  File::put($tempStubsDir.'/resources/css/app.css', '');
  // Don't create the Vue file to trigger the error

  // Temporarily replace the stubs directory
  $originalStubsDir = __DIR__.'/../../stubs';
  $backupStubsDir = __DIR__.'/../../stubs_backup';

  if (File::exists($originalStubsDir)) {
    File::move($originalStubsDir, $backupStubsDir);
  }
  File::move($tempStubsDir, $originalStubsDir);

  $this->artisan(InstallComingSoonCommand::class, ['--resources-only' => true])
    ->expectsOutputToContain('Source file does not exist')
    ->assertExitCode(0);

  // Restore the original stubs directory
  File::move($originalStubsDir, $tempStubsDir);
  if (File::exists($backupStubsDir)) {
    File::move($backupStubsDir, $originalStubsDir);
  }

  // Clean up temp directory
  if (File::exists($tempStubsDir)) {
    File::deleteDirectory($tempStubsDir);
  }
});

it('sets force to true when user confirms overwrite', function (): void {
  // Create existing files
  File::ensureDirectoryExists(resource_path('css'));
  File::put(resource_path('css/app.css'), 'existing content');

  $this->artisan(InstallComingSoonCommand::class)
    ->expectsQuestion('File css/app.css already exists. Overwrite?', true)
    ->expectsOutput('Published: css/app.css')
    ->assertExitCode(0);

  // Verify file was overwritten
  expect(File::get(resource_path('css/app.css')))->not()->toBe('existing content');
});

it('creates storage directory when it does not exist', function (): void {
  // Remove storage directory if it exists
  $storageDir = storage_path('app/public');
  if (File::exists($storageDir)) {
    File::deleteDirectory($storageDir);
  }

  $this->artisan(InstallComingSoonCommand::class, ['--assets-only' => true])
    ->assertExitCode(0);

  // Verify storage directory was created
  expect(File::exists($storageDir))->toBeTrue();
});
