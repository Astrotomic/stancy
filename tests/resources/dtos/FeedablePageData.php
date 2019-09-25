<?php

namespace Astrotomic\Stancy\Tests\PageDatas;

use Astrotomic\Stancy\Models\PageData;
use Carbon\Carbon;
use Spatie\Feed\FeedItem;

class FeedablePageData extends PageData
{
    public function toFeedItem(): FeedItem
    {
        return FeedItem::create()
            ->id('post#1')
            ->title('my first post')
            ->summary('This is my first blog post')
            ->updated(Carbon::now())
            ->link('https://example.com/blog/1')
            ->author('Gummibeer');
    }
}
