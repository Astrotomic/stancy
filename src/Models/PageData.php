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
            if ($this->isDateTime($value)) {
                $array[$key] = $value->format(DATE_RFC3339);

                continue;
            }

            if ($this->isArrayable($value)) {
                $array[$key] = $value->toArray();

                continue;
            }

            if ($this->isStringable($value)) {
                $array[$key] = $value->__toString();

                continue;
            }

            if (is_array($value)) {
                $array[$key] = $this->parseArray($value);
            }
        }

        return $array;
    }

    protected function isDateTime($value): bool
    {
        return $value instanceof DateTime;
    }

    protected function isArrayable($value): bool
    {
        return $this->isObjectWithMethod($value, 'toArray');
    }

    protected function isStringable($value): bool
    {
        return $this->isObjectWithMethod($value, '__toString');
    }

    protected function isObjectWithMethod($value, string $method): bool
    {
        return is_object($value) && method_exists($value, $method) && is_callable([$value, $method]);
    }
}
