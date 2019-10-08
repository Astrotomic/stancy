<?php

namespace Astrotomic\Stancy\Contracts;

interface FeedFactory
{
    public function makeFromSheetCollectionName(string $name): array;
}
