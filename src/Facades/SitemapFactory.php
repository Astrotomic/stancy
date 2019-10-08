<?php

namespace Astrotomic\Stancy\Facades;

use Astrotomic\Stancy\Contracts\SitemapFactory as SitemapFactoryContract;
use Illuminate\Support\Facades\Facade;
use Spatie\Sitemap\Sitemap;

/**
 * @method static Sitemap makeFromPages(array $pages)
 * @method static Sitemap makeFromSheetList(array $list)
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
