<?php

namespace Astroromic\Stancy\Models;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\HtmlString;
use Spatie\DataTransferObject\DataTransferObject;

abstract class PageData extends DataTransferObject implements Arrayable
{
    /** @var HtmlString */
    public $contents;

    public static function make(array $data): self
    {
        return new static($data);
    }
}
