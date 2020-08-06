# Getting started

## Installation

```bash
composer require astrotomic/stancy
php artisan vendor:publish --provider="Spatie\Sheets\SheetsServiceProvider" --tag=config
mkdir -p ./resources/content/static
mkdir -p ./views/content
mkdir -p ./app/Pages
```

{% code-tabs %}
{% code-tabs-item title="config/filesystems.php" %}

```php
<?php

return [
    // ...
    'disks' => [
        // ...
        'static' => [
            'driver' => 'local',
            'root' => resource_path('content/static'),
        ],
    ],
];
```

{% endcode-tabs-item %}

{% code-tabs-item title="config/sheets.php" %}

```php
<?php

return [
    'default_collection' => 'static',

    'collections' => [
        'static',
    ],
];
```

{% endcode-tabs-item %}
{% endcode-tabs %}

This is everything you have to do to prepare your first `static` sheet/page collection.

{% page-ref page="installation.md" %}

## Home

This will be an example for a simple static page - like your home/front page.

```bash
touch ./resources/content/static/home.md
touch ./views/content/home.blade.php
touch ./app/Pages/Home.php
```

These three files will represent your `home` page. The markdown file will hold the data/content, the view will render it and the PHP class will validate it and provide additional features.

{% code-tabs %}
{% code-tabs-item title="home.md" %}

```yaml
---
_view: content.static.home
_pageData: \App\Pages\Home
---
# Home

This is my first awesome Stancy page.
```

{% endcode-tabs-item %}

{% code-tabs-item title="Home.php" %}

```php
<?php

namespace App\Pages;

use Astrotomic\Stancy\Contracts\Routable;
use Astrotomic\Stancy\Models\PageData;
use Astrotomic\Stancy\Traits\PageHasContent;
use Astrotomic\Stancy\Traits\PageHasSlug;
use Astrotomic\Stancy\Traits\PageHasUrl;

class Home extends PageData implements Routable
{
    use PageHasSlug, PageHasContent, PageHasUrl;

    public function getUrl(): string
    {
        return route('static.home');
    }
}
```

{% endcode-tabs-item %}

{% code-tabs-item title="home.blade.php" %}

```markup
@extends('master')

@section('content')
    {!! $contents !!}
@endsection
```

{% endcode-tabs-item %}
{% endcode-tabs %}

Now you have to add the route to your `routes/web.php`

{% code-tabs %}
{% code-tabs-item title="routes/web.php" %}

```php
<?php

use Astrotomic\Stancy\Facades\PageFactory;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return PageFactory::makeFromSheetName('static', 'home');
})->name('static.home');
```

{% endcode-tabs-item %}
{% endcode-tabs %}

{% page-ref page="basics/content.md" %}

## Sitemap

To add your page to the sitemap and return it if someone access `/sitemap.xml` you have to do the following.

{% code-tabs %}
{% code-tabs-item title="routes/web.php" %}

```php
<?php

use Astrotomic\Stancy\Facades\SitemapFactory;
use Illuminate\Support\Facades\Route;

Route::get('/sitemap.xml', function () {
    return SitemapFactory::makeFromSheetList(['static']);
})->name('sitemap');
```

{% endcode-tabs-item %}
{% endcode-tabs %}

This will add all pages in the `static` collection to your sitemap.

{% page-ref page="advanced/sitemap.md" %}

## Blog

One of the most common examples for a page collection is a blog. You need an index page and a detail one for every article.

```bash
touch ./resources/content/static/blog.md
touch ./views/content/blog.blade.php
touch ./app/Pages/Blog.php
```

{% code-tabs %}
{% code-tabs-item title="blog.md" %}

```yaml
---
_view: static.blog
_pageData: \App\Pages\Blog
_sheets:
    posts: blog:*
---
# Blog

This is my fantastic blog index.
```

{% endcode-tabs-item %}

{% code-tabs-item title="Blog.php" %}

```php
<?php

namespace App\Pages;

use Astrotomic\Stancy\Contracts\Routable;
use Astrotomic\Stancy\Models\PageData;
use Astrotomic\Stancy\Traits\PageHasContent;
use Astrotomic\Stancy\Traits\PageHasSlug;
use Astrotomic\Stancy\Traits\PageHasUrl;

class Blog extends PageData implements Routable
{
    use PageHasSlug, PageHasContent, PageHasUrl;

    /** @var \App\Pages\Post[] */
    public $posts;

    public function getUrl(): string
    {
        return route('static.blog');
    }
}
```

{% endcode-tabs-item %}

{% code-tabs-item title="blog.blade.php" %}

```markup
@extends('master')

@section('content')
    <div class="row">
        @foreach(collect($posts)->sortByDesc('date') as $post)
            <div class="col-12 col-md-4 col-xl-3">
                <div class="card">
                    <img src="{{ url($post->image) }}" class="card-img-top">
                    <div class="card-body">
                        <h3 class="card-title">{{ $post->title }}</h3>
                        <div><small>{{ $post->date->format('Y-m-d H:i') }}</small></div>
                        <div class="card-text mb-3">{!! Illuminate\Support\Str::words(strip_tags($post->contents), 20) !!}</div>
                        <a href="{{ $post->getUrl() }}" class="btn btn-primary">open post</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
```

{% endcode-tabs-item %}
{% endcode-tabs %}

Before you can test this we will have to add our first post to the `blog` collection.

```bash
mkdir -p ./resources/content/blog
```

{% code-tabs %}
{% code-tabs-item title="config/filesystems.php" %}

```php
<?php

return [
    // ...
    'disks' => [
        // ...
        'blog' => [
            'driver' => 'local',
            'root' => resource_path('content/blog'),
        ],
    ],
];
```

{% endcode-tabs-item %}

{% code-tabs-item title="config/sheets.php" %}

```php
<?php

return [
    'default_collection' => 'static',

    'collections' => [
        'static',
        'blog',
    ],
];
```

{% endcode-tabs-item %}
{% endcode-tabs %}

```bash
touch ./resources/content/blog/first-post.md
touch ./views/content/post.blade.php
touch ./app/Pages/Post.php
```

{% code-tabs %}
{% code-tabs-item title="first-post.md" %}

```yaml
---
_view: blog.post
_pageData: \App\Pages\Post
title: my first post
image: https://via.placeholder.com/1920x1080/E91E63/FFFFFF?text=Stancy
date: 2019-09-04 17:31
---
Add you viewing ten equally believe put. Separate families my on drawings do oh offended strictly elegance. Perceive jointure be mistress by jennings properly. An admiration at he discovered difficulty continuing. We in building removing possible suitable friendly on. Nay middleton him admitting consulted and behaviour son household. Recurred advanced he oh together entrance speedily suitable. Ready tried gay state fat could boy its among shall.

Both rest of know draw fond post as. It agreement defective to excellent. Feebly do engage of narrow. Extensive repulsive belonging depending if promotion be zealously as. Preference inquietude ask now are dispatched led appearance. Small meant in so doubt hopes. Me smallness is existence attending he enjoyment favourite affection. Delivered is to ye belonging enjoyment preferred. Astonished and acceptance men two discretion. Law education recommend did objection how old.
```

{% endcode-tabs-item %}

{% code-tabs-item title="Post.php" %}

```php
<?php

namespace App\Pages;

use Astrotomic\Stancy\Contracts\Routable;
use Astrotomic\Stancy\Models\PageData;
use Astrotomic\Stancy\Traits\PageHasContent;
use Astrotomic\Stancy\Traits\PageHasDate;
use Astrotomic\Stancy\Traits\PageHasSlug;
use Astrotomic\Stancy\Traits\PageHasUrl;
use Carbon\Carbon;

class Post extends PageData implements Routable
{
    use PageHasSlug, PageHasContent, PageHasDate, PageHasUrl;

    /** @var string */
    public $title;

    /** @var string */
    public $image;

    public function __construct(array $parameters)
    {
        if (isset($parameters['date']) && is_string($parameters['date'])) {
            $parameters['date'] = Carbon::make($parameters['date']);
        }

        parent::__construct($parameters);
    }

    public function getUrl(): string
    {
        return route('blog.post', ['slug' => $this->slug]);
    }
}
```

{% endcode-tabs-item %}

{% code-tabs-item title="post.blade.php" %}

```markup
@extends('master')

@section('content')
    <img src="{{ url($image) }}" class="img-fluid">
    <h1 class="">{{ $title }}</h1>
    {!! $contents !!}
@endsection
```

{% endcode-tabs-item %}
{% endcode-tabs %}

{% code-tabs %}
{% code-tabs-item title="routes/web.php" %}

```php
<?php

use Astrotomic\Stancy\Facades\PageFactory;
use Illuminate\Support\Facades\Route;

Route::get('/blog/{slug}', function (string $slug) {
    return PageFactory::makeFromSheetName('blog', $slug);
})->name('blog.post');

Route::get('/sitemap.xml', function () {
    return SitemapFactory::makeFromSheetList([
        'static',
        'blog', // added
    ]);
});
```

{% endcode-tabs-item %}
{% endcode-tabs %}

Now you are done. If you want to add a new post just add a new markdown file to the `blog` collection and fill all required data.

## Feed

After you now have your first collection of same type pages \(blog\) it's useful to put them in a feed and link it in your HTML.

```bash
php artisan vendor:publish --provider="Spatie\Feed\FeedServiceProvider" --tag=config
```

{% code-tabs %}
{% code-tabs-item title="config/feed.php" %}

```php
<?php

use Astrotomic\Stancy\Contracts\FeedFactory;

return [
    'feeds' => [
        'blog.atom' => [
            'items' => [FeedFactory::class.'@makeFromSheetCollectionName', 'blog'],
            'url' => 'feed/blog.atom',
            'title' => 'Stancy Blog Feed',
            'description' => 'This is the Stancy Laravel demo blog feed.',
            'language' => 'en-US',
            'view' => 'feed::atom',
            'type' => 'application/atom+xml',
        ],
        'blog.rss' => [
            'items' => [FeedFactory::class.'@makeFromSheetCollectionName', 'blog'],
            'url' => 'feed/blog.rss',
            'title' => 'Blog Feed',
            'description' => 'This is the Stancy Laravel demo blog feed.',
            'language' => 'en-US',
            'view' => 'feed::rss',
            'type' => 'application/rss+xml',
        ],
    ],
];
```

{% endcode-tabs-item %}

{% code-tabs-item title="routes/web.php" %}

```php
<?php

use Illuminate\Support\Facades\Route;

Route::feeds();
```

{% endcode-tabs-item %}

{% code-tabs-item title="master.blade.php" %}

```markup
<!DOCTYPE html>
<html>
<head>
  <!-- head -->
  @include('feed::links')
</head>
<body>
  <!-- body -->
</body>
</html>
```

{% endcode-tabs-item %}
{% endcode-tabs %}

Now you have to prepare the `\App\Pages\Post` page data class.

{% code-tabs %}
{% code-tabs-item title="Post.php" %}

```php
<?php

namespace App\Pages;

use Astrotomic\Stancy\Contracts\Routable;
use Astrotomic\Stancy\Models\PageData;
use Illuminate\Support\Str;
use Spatie\Feed\FeedItem;

class Post extends PageData implements Routable
{
    // ...

    public function toFeedItem(): FeedItem
    {
        return FeedItem::create()
            ->id($this->slug)
            ->link($this->getUrl())
            ->title($this->title)
            ->author('Gummibeer, dev.gummibeer@gmail.com')
            ->updated($this->date)
            ->summary(Str::words(strip_tags($this->contents), 50, ''));
    }

    // ...
}
```

{% endcode-tabs-item %}
{% endcode-tabs %}

And you are done. You will have two feeds \(RSS and Atom\) now. If you only want one just remove the other one from the config.

{% page-ref page="advanced/feed.md" %}

**Congratulations!** 🎉 You have your first Stancy setup and are ready to go. 🚀
