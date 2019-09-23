<?php

namespace Astroromic\Stancy\Models;

use Illuminate\Contracts\Support\Arrayable;
use Spatie\DataTransferObject\DataTransferObject;

abstract class PageData extends DataTransferObject implements Arrayable
{
    public static function make(array $data): self
    {
        return new static($data);
    }
}
