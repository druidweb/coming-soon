<?php

declare(strict_types=1);

namespace Druid\Tests;

use Druid\ComingSoon\ComingSoonServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
  protected function getPackageProviders($app)
  {
    return [
      ComingSoonServiceProvider::class,
    ];
  }

  protected function getEnvironmentSetUp($app)
  {
    // Set up test environment
    $app['config']->set('app.key', 'base64:'.base64_encode(random_bytes(32)));
  }
}
