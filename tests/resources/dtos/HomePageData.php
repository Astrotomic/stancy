<?php

namespace Astrotomic\Stancy\Tests\PageDatas;

use Astrotomic\Stancy\Contracts\Routable;
use Astrotomic\Stancy\Models\PageData;
use Astrotomic\Stancy\Traits\PageHasContent;
use Astrotomic\Stancy\Traits\PageHasSlug;

class HomePageData extends PageData implements Routable
{
    use PageHasContent, PageHasSlug;

    public function getUrl(): string
    {
        return url('/');
    }
}
