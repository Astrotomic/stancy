# Feed Atom/RSS

Stancy can generate feeds using [spatie/laravel-feed](https://github.com/spatie/laravel-feed). It allows you to generate a feed for a whole page collection.

## Feed configuration

The package provides a feed factory which enables you to generate your feed by passing the page collection name.

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

## Feed links

You can generate alternate link tags for all your configured feeds and include them in your blade view.

```text
@include('feed::links')
```

