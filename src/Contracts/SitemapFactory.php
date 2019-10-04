<?php

namespace Astrotomic\Stancy\Contracts;

use Spatie\Sheets\Repository as SheetRepository;
use Spatie\Sitemap\Sitemap;

interface SitemapFactory
{
    public function makeFromSheetCollection(SheetRepository $collection): Sitemap;

    public function makeFromSheetCollectionName(string $name): Sitemap;
}
