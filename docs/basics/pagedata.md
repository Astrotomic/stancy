# PageData

Stancy uses [spatie/data-transfer-object](https://github.com/spatie/data-transfer-object) to validate the passed in data and allow static analysis.

## Properties

Your page data class should extend `\Astrotomic\Stancy\Models\PageData` and define all possible attributes via public properties including a doc-tag with all possible types \(FQCN for objects\).

{% code-tabs %}
{% code-tabs-item title="app/Pages/Blog.php" %}
```php
<?php

namespace App\Pages;

class Blog extends PageData
{
    /** @var string */
    public $title;
    
    /** @var \Carbon\Carbon */
    public $date;

    /** @var \App\Pages\Post[] */
    public $posts;
}
```
{% endcode-tabs-item %}
{% endcode-tabs %}

## Traits

The package comes with some ready to use traits with the most common attributes.

* `\Astrotomic\Stancy\Traits\PageHasContent` adds the `contents` attribute \(used by  markdown content parsers\)
* `\Astrotomic\Stancy\Traits\PageHasDate` adds the `date` attribute \(used by `\Spatie\Sheets\PathParsers\SlugWithDateParser`\)
* `\Astrotomic\Stancy\Traits\PageHasOrder` adds the `order` attribute \(used by `\Spatie\Sheets\PathParsers\SlugWithOrderParser`\)
* `\Astrotomic\Stancy\Traits\PageHasSlug` adds the `slug` attribute \(used by path parsers\)

{% code-tabs %}
{% code-tabs-item title="app/Pages/Blog.php" %}
```php
<?php

namespace App\Pages;

use Astrotomic\Stancy\Models\PageData;
use Astrotomic\Stancy\Traits\PageHasContent;
use Astrotomic\Stancy\Traits\PageHasSlug;

class Blog extends PageData
{
    use PageHasSlug, PageHasContent;
}
```
{% endcode-tabs-item %}
{% endcode-tabs %}

## Routable & PageHasUrl

The package comes with a `\Astrotomic\Stancy\Contracts\Routable` interface which defines a `getUrl()` method. You can combine it with the `\Astrotomic\Stancy\Traits\PageHasUrl` trait which defines the `toSitemapItem()` method by using the `getUrl()` return value. It's also great to get the page url in a view or for the feed item transformation.

{% code-tabs %}
{% code-tabs-item title="app/Pages/Blog.php" %}
```php
<?php

namespace App\Pages;

use Astrotomic\Stancy\Contracts\Routable;
use Astrotomic\Stancy\Models\PageData;
use Astrotomic\Stancy\Traits\PageHasUrl;

class Blog extends PageData implements Routable
{
    use PageHasUrl;

    public function getUrl(): string
    {
        return url('/blog');
    }
}
```
{% endcode-tabs-item %}
{% endcode-tabs %}

