<?php

namespace Astrotomic\Stancy;

use Astrotomic\Stancy\Contracts\Page as PageContract;
use Astrotomic\Stancy\Contracts\PageFactory as PageFactoryContract;
use Astrotomic\Stancy\Factories\PageFactory;
use Astrotomic\Stancy\Models\Page;
use Illuminate\Support\ServiceProvider;

class StancyServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/stancy.php' => config_path('stancy.php'),
        ], 'config');
    }

    public function register(): void
    {
        $this->registerConfig();
        $this->registerPage();
    }

    protected function registerConfig(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/stancy.php', 'stancy'
        );
    }

    protected function registerPage(): void
    {
        $this->app->singleton(PageFactoryContract::class, PageFactory::class);
        $this->app->bind(PageContract::class, Page::class);
    }
}
