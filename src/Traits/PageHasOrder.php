<?php

namespace Astrotomic\Stancy\Traits;

use Spatie\Sheets\PathParsers\SlugWithOrderParser;

/**
 * @see SlugWithOrderParser
 */
trait PageHasOrder
{
    /**
     * @var int|float
     * @see SlugWithOrderParser
     */
    public $order;
}
