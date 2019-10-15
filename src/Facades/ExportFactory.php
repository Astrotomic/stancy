<?php

namespace Astrotomic\Stancy\Facades;

use Astrotomic\Stancy\Contracts\ExportFactory as ExportFactoryContract;
use Illuminate\Support\Facades\Facade;

/**
 * @method static ExportFactoryContract addSheetList(string[] $list)
 * @method static ExportFactoryContract addSheetCollectionName(string $name)
 * @method static ExportFactoryContract addFeeds(string[] $except = [])
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
