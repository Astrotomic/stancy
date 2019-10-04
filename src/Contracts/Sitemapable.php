<?php

namespace Astrotomic\Stancy\Contracts;

use Spatie\Sitemap\Tags\Tag;

interface Sitemapable
{
    public function toSitemapItem(): Tag;
}
