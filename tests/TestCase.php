<?php

namespace Astrotomic\Stancy\Tests;

use Astrotomic\Stancy\Contracts\PageFactory as PageFactoryContract;
use Astrotomic\Stancy\StancyServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\Sheets\SheetsServiceProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

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
        $app['config']->set('filesystems.disks.blog', [
            'driver' => 'local',
            'root' => realpath(__DIR__.'/resources/content/blog'),
        ]);

        $app['config']->set('sheets.collections', [
            'content',
            'blog',
        ]);
    }

    protected function getRequest(string $url = '/', array $headers = []): Request
    {
        return Request::createFromBase(
            SymfonyRequest::create(
                $this->prepareUrlForRequest($url),
                'GET',
                [],
                [],
                [],
                $this->transformHeadersToServerVars($headers)
            )
        );
    }

    protected function getPageFactory(): PageFactoryContract
    {
        return $this->app->make(PageFactoryContract::class);
    }
}
