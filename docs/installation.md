# Installation

## Install package

Add the package in your `composer.json` by executing the command.

```bash
composer require astrotomic/stancy
```

## Configure dependencies

### spatie/sheets

Stancy uses [spatie/sheets](https://github.com/spatie/sheets) as base - a sheet is the data file which is used to fill a page. By default sheets can be Markdown \(with YAML frontmatter\), JSON or YAML files. But you can define your own [content parsers](https://github.com/spatie/sheets#content-parser). To use sheets you have to [create a collection](https://github.com/spatie/sheets#creating-your-first-collection) which is a folder that contains your sheets. You can define as much collections as you want.

{% code-tabs %}
{% code-tabs-item title="config/sheets.php" %}

```php
<?php

use Spatie\Sheets\ContentParsers\JsonParser;

return [
    'default_collection' => 'static',

    'collections' => [
        'static',
        'blog',
        'data' => [
            'content_parser' => JsonParser::class,
            'extension' => 'json',
        ],
    ],
];
```

{% endcode-tabs-item %}

{% code-tabs-item title="config/filesystems.php" %}

```php
<?php

return [
    'disks' => [
        'static' => [
            'driver' => 'local',
            'root' => resource_path('content/static'),
        ],
        'blog' => [
            'driver' => 'local',
            'root' => resource_path('content/blog'),
        ],
        'data' => [
            'driver' => 'local',
            'root' => resource_path('content/data'),
        ],
    ],
];
```

{% endcode-tabs-item %}
{% endcode-tabs %}

{% page-ref page="basics/content.md" %}

### spatie/laravel-feeds

Stancy comes with feed \(Atom & RSS\) support which is provided by [spatie/laravel-feeds](https://github.com/spatie/laravel-feed). A feed is generated from a sheet collection. Below is an example feed configuration which uses the `\Astrotomic\Stancy\Contracts\FeedFactory`.

{% code-tabs %}
{% code-tabs-item title="config/feed.php" %}

```php
<?php

use Astrotomic\Stancy\Contracts\FeedFactory;

return [
    'feeds' => [
        'blog' => [
            'items' => [FeedFactory::class.'@makeFromSheetCollectionName', 'blog'],
            'url' => 'feed/blog.atom',
            'title' => 'Stancy Blog Feed',
            'description' => 'This is the Stancy blog feed.',
            'language' => 'en-US',
            'view' => 'feed::atom',
            'type' => 'application/atom+xml',
        ],
    ],
];
```

{% endcode-tabs-item %}
{% endcode-tabs %}

{% page-ref page="advanced/feed.md" %}

### spatie/laravel-export

Stancy integrates [spatie/laravel-export](https://github.com/spatie/laravel-export) to generate static files. You can configure the exporter in the config file or use the `\Astrotomic\Stancy\Contracts\ExportFactory` to add your pages programmatically. We recommend to disable the `crawl` option in the `config/export.php` and use the export factory to add pages to your export list.

{% page-ref page="advanced/static-export.md" %}
