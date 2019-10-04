<?php

namespace Astrotomic\Stancy\Facades;

use Astrotomic\Stancy\Contracts\SitemapFactory as SitemapFactoryContract;
use Illuminate\Support\Facades\Facade;
use Spatie\Sheets\Repository as SheetRepository;
use Spatie\Sitemap\Sitemap;

/**
 * @method static Sitemap makeFromSheetCollection(SheetRepository $collection)
 * @method static Sitemap makeFromSheetCollectionName(string $name)
 *
 * @see SitemapFactoryContract
 */
class SitemapFactory extends Facade
{
    protected static function getFacadeAccessor()
    {
        return SitemapFactoryContract::class;
    }
}
