<?php

namespace Astrotomic\Stancy\Traits;

use Spatie\Sheets\PathParsers\SlugWithOrderParser;

/**
 * @see SlugWithOrderParser
 */
trait PageHasOrder
{
    /** @var int @see SlugWithOrderParser */
    public $order;
}
