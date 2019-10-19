<?php

namespace Astrotomic\Stancy\Models;

use Astrotomic\Stancy\Contracts\Sitemapable;
use DateTime;
use Exception;
use Illuminate\Contracts\Support\Arrayable;
use Spatie\DataTransferObject\DataTransferObject;
use Spatie\Feed\Feedable;
use Spatie\Feed\FeedItem;
use Spatie\Sitemap\Tags\Tag;

abstract class PageData extends DataTransferObject implements Arrayable, Feedable, Sitemapable
{
    public static function make(array $data): self
    {
        return new static($data);
    }

    public function toFeedItem(): FeedItem
    {
        throw new Exception(sprintf('You have to define the transformation to a valid %s yourself if you want to use a feed.', FeedItem::class));
    }

    public function toSitemapItem(): Tag
    {
        throw new Exception(sprintf('You have to define the transformation to a valid %s yourself if you want to use a sitemap.', Tag::class));
    }

    // ToDo: https://github.com/spatie/data-transfer-object/issues/64
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
