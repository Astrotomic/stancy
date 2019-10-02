<?php

namespace Astrotomic\Stancy\Contracts;

use Astrotomic\Stancy\Models\Page;

interface PageFactory
{
    public function make(array $data = [], ?string $page = null): Page;

    public function makeFromSheet(string $collection, string $name, ?string $page = null): Page;
}
