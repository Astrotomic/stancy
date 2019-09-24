<?php

namespace Astrotomic\Stancy\Tests;

use Astrotomic\Stancy\StancyServiceProvider;
use Illuminate\Support\Facades\View;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\Sheets\SheetsServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        View::addLocation(__DIR__.'/resources/views');
    }

    protected function getPackageProviders($app): array
    {
        return [
            StancyServiceProvider::class,
        ];
    }

    protected function getApplicationProviders($app): array
    {
        return array_merge(parent::getApplicationProviders($app), [
            SheetsServiceProvider::class,
        ]);
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('filesystems.disks.content', [
            'driver' => 'local',
            'root' => realpath(__DIR__.'/resources/content'),
        ]);

        $app['config']->set('sheets.collections.content', [
            'disk' => 'content',
        ]);
    }
}
