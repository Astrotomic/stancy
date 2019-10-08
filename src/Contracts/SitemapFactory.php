<?php

namespace Astrotomic\Stancy\Contracts;

use Spatie\Sitemap\Sitemap;

interface SitemapFactory
{
    public function makeFromSheetList(array $list): Sitemap;

    public function makeFromSheetCollectionName(string $name): Sitemap;
}
