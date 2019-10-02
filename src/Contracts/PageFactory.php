<?php

namespace Astrotomic\Stancy\Contracts;

use Astrotomic\Stancy\Contracts\Page as PageContract;

interface PageFactory
{
    public function make(array $data = [], ?string $page = null): PageContract;

    public function makeFromSheet(string $collection, string $name, ?string $page = null): PageContract;
}
