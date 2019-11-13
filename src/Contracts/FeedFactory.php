<?php

namespace Astrotomic\Stancy\Contracts;

use Illuminate\Support\Collection;

interface FeedFactory
{
    public function makeFromSheetCollectionName(string $name): Collection;
}
