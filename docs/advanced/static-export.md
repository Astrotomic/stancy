# static export

Stancy integrates [spatie/laravel-export](https://github.com/spatie/laravel-export) to export your pages as static files.

## Configuration

We recommend to disable the `crawl` option and export only listed paths/pages. The `paths` option can be empty if you only want to export feeds and/or pages. But you also can add additional paths, like the sitemap, to export.

{% code-tabs %}
{% code-tabs-item title="config/export.php" %}
```php
<?php

return [
    // to only export listed paths/pages
    'crawl' => false,

    'paths' => [
        '/sitemap.xml',
    ],
];
```
{% endcode-tabs-item %}
{% endcode-tabs %}

## prepare Pages

By default you can't export any page, only the feeds. To allow page export you have to implement the `\Astrotomic\Stancy\Contracts\Routable` interface in your [page data class](../basics/pagedata.md). This interface is also good to add your pages to a [sitemap](sitemap.md) and [feeds](feed.md).

{% code-tabs %}
{% code-tabs-item title="app/Pages/Home.php" %}
```php
<?php

namespace App\Pages;

use Astrotomic\Stancy\Contracts\Routable;
use Astrotomic\Stancy\Models\PageData;

class Home extends PageData implements Routable
{
    public function getUrl(): string
    {
        return url('/');
    }
}
```
{% endcode-tabs-item %}
{% endcode-tabs %}

## ExportFactory

To add your pages programmatically you should use a service provider. This service provider should be the last or at least after `\App\Providers\RouteServiceProvider` in your `app.providers` configuration. In the service provider you should use the `boot()` method where you can inject the `\Astrotomic\Stancy\Contracts\ExportFactory` service and add a new `booted` event listener.

{% code-tabs %}
{% code-tabs-item title="app/Providers/ExportServiceProvider.php" %}
```php
<?php

namespace App\Providers;

use Astrotomic\Stancy\Contracts\ExportFactory as ExportFactoryContract;
use Illuminate\Support\ServiceProvider;

class ExportServiceProvider extends ServiceProvider
{
    public function boot(ExportFactoryContract $exportFactory)
    {
        $this->app->booted(function () use ($exportFactory) {
            $exportFactory
                ->addFeeds()
                ->addSheetList(['static:home', 'static:blog'])
                ->addSheetCollectionName('blog')
            ;
        });
    }
}
```
{% endcode-tabs-item %}
{% endcode-tabs %}

This service provider will add all [feeds](feed.md), blog posts and the `home` and `blog` static pages.

