<?php

namespace Astrotomic\Stancy\Tests\PageDatas;

use Astrotomic\Stancy\Models\PageData;
use Astrotomic\Stancy\Traits\PageHasContent;
use Astrotomic\Stancy\Traits\PageHasDate;
use Astrotomic\Stancy\Traits\PageHasSlug;

class ParseArrayPageData extends PageData
{
    use PageHasContent, PageHasSlug, PageHasDate;

    /** @var array */
    public $array;

    /** @var \Illuminate\Support\Collection */
    public $collection;
}
