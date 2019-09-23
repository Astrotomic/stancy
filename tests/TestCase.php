<?php

namespace Astroromic\Stancy\Tests;

use Astroromic\Stancy\StancyServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            StancyServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
    }
}
