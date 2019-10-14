<?php

namespace Astrotomic\Stancy\Tests;

use Astrotomic\Stancy\Contracts\FeedFactory as FeedFactoryContract;
use Astrotomic\Stancy\Contracts\PageFactory as PageFactoryContract;
use Astrotomic\Stancy\Contracts\SitemapFactory as SitemapFactoryContract;
use Astrotomic\Stancy\Contracts\ExportFactory as ExportFactoryContract;
use Astrotomic\Stancy\StancyServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\Export\ExportServiceProvider;
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
            ExportServiceProvider::class,
            FeedServiceProvider::class,
            SheetsServiceProvider::class,
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
        $app['config']->set('filesystems.disks.export', [
            'driver' => 'local',
            'root' => realpath(__DIR__.'/export'),
        ]);

        $app['config']->set('sheets.collections', [
            'content',
            'blog',
        ]);

        $app['config']->set('feed.feeds', []);
        $app['config']->set('feed.feeds.blog', [
            'title' => 'test blog feed',
            'items' => [FeedFactoryContract::class.'@makeFromSheetCollectionName', 'blog'],
            'url' => '/feed/blog.atom',
            'view' => 'feed::atom',
        ]);

        $app['config']->set('export.disk', 'export');
        $app['config']->set('export.crawl', false);
        $app['config']->set('export.clean_before_export', true);
        $app['config']->set('export.include_files', []);
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

    protected function getExportFactory(): ExportFactoryContract
    {
        return $this->app->make(ExportFactoryContract::class);
    }
}
