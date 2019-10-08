<?php

namespace Astrotomic\Stancy\Facades;

use Astrotomic\Stancy\Contracts\FeedFactory as FeedFactoryContract;
use Illuminate\Support\Facades\Facade;

/**
 * @method static array makeFromSheetCollectionName(string $name)
 *
 * @see FeedFactoryContract
 */
class FeedFactory extends Facade
{
    protected static function getFacadeAccessor()
    {
        return FeedFactoryContract::class;
    }
}
