<?php

namespace Astroromic\Stancy;

use Astroromic\Stancy\Models\Page;
use Illuminate\Support\ServiceProvider;
use Spatie\SchemaOrg\Graph;

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
        $this->registerSchemaOrg();
        $this->registerPage();
    }

    protected function registerConfig(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/stancy.php', 'stancy'
        );
    }

    protected function registerSchemaOrg(): void
    {
        $this->app->singleton(Graph::class);
    }

    protected function registerPage(): void
    {
        $this->app->bind(Page::class);
    }
}
