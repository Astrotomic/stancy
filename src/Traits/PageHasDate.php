<?php

namespace Astrotomic\Stancy\Traits;

use Spatie\Sheets\PathParsers\SlugWithDateParser;

/**
 * @see SlugWithDateParser
 */
trait PageHasDate
{
    /** @var \Carbon\Carbon */
    public $date;
}
