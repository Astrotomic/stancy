<?php

namespace Astrotomic\Stancy\Models;

use DateTime;
use Illuminate\Contracts\Support\Arrayable;
use Spatie\DataTransferObject\DataTransferObject;

abstract class PageData extends DataTransferObject implements Arrayable
{
    public static function make(array $data): self
    {
        return new static($data);
    }

    // https://github.com/spatie/data-transfer-object/issues/64
    protected function parseArray(array $array): array
    {
        foreach ($array as $key => $value) {
            if (is_object($value)) {
                if($value instanceof DateTime) {
                    $array[$key] = $value->format(DATE_RFC3339);

                    continue;
                }

                if (method_exists($value, 'toArray')) {
                    $array[$key] = $value->toArray();

                    continue;
                }

                if (method_exists($value, '__toString')) {
                    $array[$key] = $value->__toString();

                    continue;
                }
            }

            if (is_array($value)) {
                $array[$key] = $this->parseArray($value);
            }
        }

        return $array;
    }
}
