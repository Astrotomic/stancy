<?php

namespace Astrotomic\Stancy\Contracts;

interface ExportFactory
{
    public function addSheetList(array $list): self;

    public function addSheetCollectionName(string $name): self;

    public function addFeeds(array $except = []): self;
}
