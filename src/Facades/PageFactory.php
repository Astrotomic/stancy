<?php

namespace Astrotomic\Stancy\Facades;

use Illuminate\Support\Facades\Facade;
use Astrotomic\Stancy\Contracts\PageFactory as AstrotomicPageFactory;

/**
 * @method PageContract make(array $data = [], ?string $page = null) Creates a Page
 * @method PageContract makeFromSheet(string $collection, string $name, ?string $page = null) Creates a Page from a Sheet
 * @see \Astrotomic\Stancy\Contracts\PageFactory
 */
class PageFactory extends Facade
{
    protected static function getFacadeAccessor()
    {
        return AstrotomicPageFactory::class;
    }
}
