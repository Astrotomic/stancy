<?php

namespace Astrotomic\Stancy\Contracts;

use Spatie\Sheets\Repository as SheetRepository;

interface FeedFactory
{
    public function makeFromSheetCollection(SheetRepository $collection): array;

    public function makeFromSheetCollectionName(string $name): array;
}
