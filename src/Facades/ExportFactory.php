<?php

namespace Astrotomic\Stancy\Facades;

use Astrotomic\Stancy\Contracts\ExportFactory as ExportFactoryContract;
use Illuminate\Support\Facades\Facade;

/**
 * @method static void addSheetList(string[] $list)
 * @method static void addSheetCollectionName(string $name)
 * @method static void addFeeds(string[] $except = [])
 *
 * @see SitemapFactoryContract
 */
class ExportFactory extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ExportFactoryContract::class;
    }
}
