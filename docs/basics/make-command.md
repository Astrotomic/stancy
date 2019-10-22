# Make Page Command

The `make:page` command allows you to create a new page - including [page data class](pagedata.md) and [content file](content.md).

```bash
php artisan make:page Post --collection=blog
```

This command will create the following two files.

{% code-tabs %}
{% code-tabs-item title="app/Pages/Post.php" %}
```php
<?php

namespace App\Pages;

use Astrotomic\Stancy\Models\PageData;
use Astrotomic\Stancy\Traits\PageHasContent;
use Astrotomic\Stancy\Traits\PageHasDate;
use Astrotomic\Stancy\Traits\PageHasOrder;
use Astrotomic\Stancy\Traits\PageHasSlug;

class Post extends PageData
{

}
```
{% endcode-tabs-item %}

{% code-tabs-item title="resources/content/blog/post.md" %}
```
---
_pageData: \App\Pages\Post
_view: null
---
```
{% endcode-tabs-item %}
{% endcode-tabs %}

After this you can rename the created markdown file, add the view to use and your content.

{% hint style="info" %}
The `make:page` command works with sheet collections with markdown, JSON or YAML content \(`.md`, `.json`, `.yaml`, `.yml`\). For all other extensions it will only create an empty file.
{% endhint %}



