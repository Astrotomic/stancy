<?php

namespace Astrotomic\Stancy\Tests\PageDatas;

use Astrotomic\Stancy\Models\PageData;
use Astrotomic\Stancy\Traits\PageHasContent;
use Astrotomic\Stancy\Traits\PageHasSlug;
use Carbon\Carbon;
use Spatie\Feed\FeedItem;
use Spatie\Sitemap\Tags\Tag;
use Spatie\Sitemap\Tags\Url;

class BlogPostPageData extends PageData
{
    use PageHasContent, PageHasSlug;

    public function toFeedItem(): FeedItem
    {
        return FeedItem::create()
            ->id($this->slug)
            ->title($this->slug)
            ->updated(Carbon::now())
            ->summary($this->contents)
            ->link(url($this->slug))
            ->author('Gummibeer');
    }

    public function toSitemapItem(): Tag
    {
        return Url::create(url($this->slug));
    }
}
