<?php

namespace Astroromic\Stancy;

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
}
