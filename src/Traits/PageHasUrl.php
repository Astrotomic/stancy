<?php

namespace Astrotomic\Stancy\Traits;

use Spatie\Sitemap\Tags\Tag;
use Spatie\Sitemap\Tags\Url;

trait PageHasUrl
{
    abstract public function getUrl(): string;

    public function toSitemapItem(): Tag
    {
        return Url::create($this->getUrl());
    }
}
