<?php

namespace Astrotomic\Stancy\Tests\PageDatas;

use Astrotomic\Stancy\Contracts\Routable;
use Astrotomic\Stancy\Models\PageData;
use Astrotomic\Stancy\Traits\PageHasContent;
use Astrotomic\Stancy\Traits\PageHasSlug;
use Astrotomic\Stancy\Traits\PageHasUrl;
use Carbon\Carbon;
use Spatie\Feed\FeedItem;

class BlogPostPageData extends PageData implements Routable
{
    use PageHasContent, PageHasSlug, PageHasUrl;

    public function toFeedItem(): FeedItem
    {
        return FeedItem::create()
            ->id($this->slug)
            ->title($this->slug)
            ->updated(Carbon::now())
            ->summary($this->contents)
            ->link($this->getUrl())
            ->author('Gummibeer');
    }

    public function getUrl(): string
    {
        return url($this->slug);
    }
}
