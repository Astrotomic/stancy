<?php

namespace Astrotomic\Stancy\Tests\PageDatas;

use Astrotomic\Stancy\Models\PageData;
use Astrotomic\Stancy\Traits\PageHasContent;
use Astrotomic\Stancy\Traits\PageHasSlug;

class BlogPostPageData extends PageData
{
    use PageHasContent, PageHasSlug;
}
