<?php

namespace Astroromic\Stancy\Facades;

use Illuminate\Support\Facades\Facade;
use Spatie\SchemaOrg\Graph;

class SchemaOrgGraph extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Graph::class;
    }
}
