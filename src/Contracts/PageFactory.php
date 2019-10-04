<?php

namespace Astrotomic\Stancy\Contracts;

use Astrotomic\Stancy\Contracts\Page as PageContract;
use Spatie\Sheets\Sheet;

interface PageFactory
{
    public function make(array $data = [], ?string $page = null): PageContract;

    public function makeFromSheet(Sheet $sheet, ?string $page = null): PageContract;

    public function makeFromSheetName(string $collection, string $name, ?string $page = null): PageContract;
}
