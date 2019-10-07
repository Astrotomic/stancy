<?php

namespace Astrotomic\Stancy\Facades;

use Astrotomic\Stancy\Contracts\Page as PageContract;
use Astrotomic\Stancy\Contracts\PageFactory as PageFactoryContract;
use Illuminate\Support\Facades\Facade;

/**
 * @method static PageContract make(array $data = [], ?string $page = null)
 * @method static PageContract makeFromSheet(string $collection, string $name, ?string $page = null)
 * @method static PageContract makeFromSheetName(string $collection, string $name, ?string $page = null)
 * @see PageFactoryContract
 */
class PageFactory extends Facade
{
    protected static function getFacadeAccessor()
    {
        return PageFactoryContract::class;
    }
}
