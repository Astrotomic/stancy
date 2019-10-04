<?php

namespace Astrotomic\Stancy\Facades;

use Astrotomic\Stancy\Contracts\FeedFactory as FeedFactoryContract;
use Illuminate\Support\Facades\Facade;
use Spatie\Sheets\Repository as SheetRepository;

/**
 * @method static array makeFromSheetCollection(SheetRepository $collection)
 * @method static array makeFromSheetCollectionName(string $name)
 *
 * @see \Astrotomic\Stancy\Contracts\FeedFactory
 */
class FeedFactory extends Facade
{
    protected static function getFacadeAccessor()
    {
        return FeedFactoryContract::class;
    }
}
