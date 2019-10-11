# Sitemap

Stancy can generate a sitemap using [spatie/laravel-sitemap](https://github.com/spatie/laravel-sitemap). It allows you to add whole collections but also single pages to a sitemap.

## prepare Pages

By default you can't add a page to your sitemap. You have to use a `\Astrotomic\Stancy\Models\PageData` class which defines the `toSitemapItem()` method. The easiest way is to use the `\Astrotomic\Stancy\Contracts\Routable` interface and `\Astrotomic\Stancy\Traits\PageHasUrl` trait. After this you only have to define the `getUrl()` method which is also useful to add the page to a [feed](feed.md) or generate a link to it in a view.

{% code-tabs %}
{% code-tabs-item title="app/Pages/Post.php" %}
```php
<?php

namespace App\Pages;

use Astrotomic\Stancy\Contracts\Routable;
use Astrotomic\Stancy\Models\PageData;
use Astrotomic\Stancy\Traits\PageHasContent;
use Astrotomic\Stancy\Traits\PageHasSlug;
use Astrotomic\Stancy\Traits\PageHasUrl;

class Post extends PageData implements Routable
{
    use PageHasSlug, PageHasContent, PageHasUrl;

    public function getUrl(): string
    {
        return route('blog.post', ['post' => $this->slug]);
    }
}
```
{% endcode-tabs-item %}
{% endcode-tabs %}

## Sitemap response

The following code snippet will show you how to return a sitemap within your routes. The sitemap will contain all pages in the `static` and `blog` collection.

{% code-tabs %}
{% code-tabs-item title="routes/web.php" %}
```php
<?php

use Astrotomic\Stancy\Facades\SitemapFactory;

Route::get('/sitemap.xml', function () {
    return SitemapFactory::makeFromSheetList(['static', 'blog']);
});
```
{% endcode-tabs-item %}
{% endcode-tabs %}

