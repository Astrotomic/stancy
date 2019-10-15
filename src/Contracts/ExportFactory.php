<?php

namespace Astrotomic\Stancy\Contracts;

interface ExportFactory
{
    public function addSheetList(array $list): void;

    public function addSheetCollectionName(string $name): void;

    public function addFeeds(array $except = []): void;
}
