<?php

namespace Astrotomic\Stancy;

use Astrotomic\Stancy\Contracts\FeedFactory as FeedFactoryContract;
use Astrotomic\Stancy\Contracts\Page as PageContract;
use Astrotomic\Stancy\Contracts\PageFactory as PageFactoryContract;
use Astrotomic\Stancy\Contracts\SitemapFactory as SitemapFactoryContract;
use Astrotomic\Stancy\Factories\FeedFactory;
use Astrotomic\Stancy\Factories\PageFactory;
use Astrotomic\Stancy\Factories\SitemapFactory;
use Astrotomic\Stancy\Models\Page;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class StancyServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->registerPage();
        $this->registerFeed();
        $this->registerSitemap();
    }

    public function provides(): array
    {
        return [
            PageFactoryContract::class,
            PageContract::class,
            FeedFactoryContract::class,
            SitemapFactoryContract::class,
        ];
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
}
