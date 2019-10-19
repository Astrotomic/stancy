# Content

Stancy uses [spatie/sheets](https://github.com/spatie/sheets) to parse your data files. This allows you to use Markdown \(with YAML frontmatter\), JSON or YAML files.

## Collections

Before you can start creating content you have to configure your collection\(s\).

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
{% endcode-tabs %}

The above configuration will add three collections `static`, `blog` and `data`. The first two use the markdown with YAML frontmatter by default but the `data` collection uses JSON.

## Sheets

The single files in a collection are called "sheet". You have full control over the content of your sheets.

### predefined Variables

Pages use some predefined variables which you can use to tell the page how to handle the data.

* `_view` defines the view to use if the page get's rendered
* `_pageData` defines the [PageData](pagedata.md) class to use
* `_sheets` defines a list of additional sheets or collections to load

{% code-tabs %}
{% code-tabs-item title="static/blog.md" %}
```yaml
---
_view: page.blog
_pageData: \App\Pages\Blog
_sheets:
    posts: blog:*
---

# Blog
```
{% endcode-tabs-item %}

{% code-tabs-item title="blog/first-post.md" %}
```yaml
---
_view: blog.post
_pageData: \App\Pages\Post
title: my first post
---
```
{% endcode-tabs-item %}

{% code-tabs-item title="blog/second-post.md" %}
```yaml
---
_view: blog.post
_pageData: \App\Pages\Post
title: my second post
---
```
{% endcode-tabs-item %}
{% endcode-tabs %}

These markdown files with YAML frontmatter will generate the following array that's passed to your view.

```php
[
    'slug' => 'blog',
    'contents' => '<h1>Blog</h1>',
    'posts' => [
        [
            'slug' => 'first-post',
            'title' => 'my first post',
        ], 
        [
            'slug' => 'second-post',
            'title' => 'my second post',
        ]
    ]
]
```

{% hint style="info" %}
This example array does not respect the defined page data classes. Head to the [PageData](pagedata.md) page to learn more about them and what you can do with them.
{% endhint %}

