<?php

namespace Astrotomic\Stancy;

use Astrotomic\Stancy\Commands\MakePageCommand;
use Astrotomic\Stancy\Contracts\ExportFactory as ExportFactoryContract;
use Astrotomic\Stancy\Contracts\FeedFactory as FeedFactoryContract;
use Astrotomic\Stancy\Contracts\Page as PageContract;
use Astrotomic\Stancy\Contracts\PageFactory as PageFactoryContract;
use Astrotomic\Stancy\Contracts\SitemapFactory as SitemapFactoryContract;
use Astrotomic\Stancy\Factories\ExportFactory;
use Astrotomic\Stancy\Factories\FeedFactory;
use Astrotomic\Stancy\Factories\PageFactory;
use Astrotomic\Stancy\Factories\SitemapFactory;
use Astrotomic\Stancy\Models\Page;
use Illuminate\Support\ServiceProvider;

class StancyServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->commands([
            MakePageCommand::class,
        ]);
    }

    public function register(): void
    {
        $this->registerPage();
        $this->registerFeed();
        $this->registerSitemap();
        $this->registerExporter();
    }

    protected function registerPage(): void
    {
        $this->app->singleton(PageFactoryContract::class, PageFactory::class);
        $this->app->bind(PageContract::class, Page::class);
    }

    protected function registerFeed(): void
    {
        $this->app->singleton(FeedFactoryContract::class, FeedFactory::class);
    }

    protected function registerSitemap(): void
    {
        $this->app->singleton(SitemapFactoryContract::class, SitemapFactory::class);
    }

    protected function registerExporter(): void
    {
        $this->app->singleton(ExportFactoryContract::class, ExportFactory::class);
    }
}
