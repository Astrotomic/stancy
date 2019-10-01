<?php

namespace Astrotomic\Stancy\Tests\PageDatas;

use Astrotomic\Stancy\Models\PageData;
use Astrotomic\Stancy\Traits\PageHasContent;
use Astrotomic\Stancy\Traits\PageHasSlug;

class BlogPageData extends PageData
{
    use PageHasContent, PageHasSlug;

    /** @var \Astrotomic\Stancy\Tests\PageDatas\BlogPostPageData[] */
    public $posts;

    /** @var \Astrotomic\Stancy\Tests\PageDatas\HomePageData */
    public $home;
}
