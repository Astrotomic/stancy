<?php

namespace Astrotomic\Stancy\Tests;

use Astrotomic\Stancy\Contracts\FeedFactory as FeedFactoryContract;
use Astrotomic\Stancy\Contracts\PageFactory as PageFactoryContract;
use Astrotomic\Stancy\Contracts\SitemapFactory as SitemapFactoryContract;
use Astrotomic\Stancy\StancyServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\Feed\FeedServiceProvider;
use Spatie\Sheets\SheetsServiceProvider;
use Spatie\Sitemap\SitemapServiceProvider;
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
            // dependencies
            SheetsServiceProvider::class,
            FeedServiceProvider::class,
            SitemapServiceProvider::class,
            // stancy
            StancyServiceProvider::class,
        ];
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

    protected function getFeedFactory(): FeedFactoryContract
    {
        return $this->app->make(FeedFactoryContract::class);
    }

    protected function getSitemapFactory(): SitemapFactoryContract
    {
        return $this->app->make(SitemapFactoryContract::class);
    }
}
