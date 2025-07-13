<?php

declare(strict_types=1);

use Druid\ComingSoon\Console\Commands\InstallComingSoonCommand;
use Illuminate\Support\Facades\File;

it('shows error message when file copy fails', function (): void {
  // Create a command that we can test directly
  $command = new InstallComingSoonCommand;
  $command->setLaravel(app());

  // Set up the command with proper input/output
  $input = new \Symfony\Component\Console\Input\ArrayInput([]);
  $output = new \Symfony\Component\Console\Output\BufferedOutput;
  $outputStyle = new \Illuminate\Console\OutputStyle($input, $output);
  $command->setOutput($outputStyle);

  // Use reflection to access the protected publishFile method
  $reflection = new \ReflectionClass($command);
  $reflectionMethod = $reflection->getMethod('publishFile');
  $reflectionMethod->setAccessible(true);

  // Mock File facade completely to control the copy failure
  File::shouldReceive('exists')
    ->with('/fake/source.txt')
    ->andReturn(true);

  File::shouldReceive('exists')
    ->with('/fake/dest.txt')
    ->andReturn(false);

  File::shouldReceive('exists')
    ->with('/fake')
    ->andReturn(true);

  File::shouldReceive('copy')
    ->with('/fake/source.txt', '/fake/dest.txt')
    ->andReturn(false); // This will make the copy fail

  // Call the method with fake paths
  $reflectionMethod->invoke($command, 'test-file', '/fake/source.txt', '/fake/dest.txt', false);

  // Check that error message was output
  $outputContent = $output->fetch();
  expect($outputContent)->toContain('Failed to publish: test-file');
});
