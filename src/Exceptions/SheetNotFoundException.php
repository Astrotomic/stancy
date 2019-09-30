<?php

namespace Astrotomic\Stancy\Exceptions;

use Astrotomic\Stancy\Solutions\AddSheetToCollectionSolution;
use Facade\IgnitionContracts\ProvidesSolution;
use Facade\IgnitionContracts\Solution;
use RuntimeException;

class SheetNotFoundException extends RuntimeException implements ProvidesSolution
{
    /** @var string */
    protected $collection;

    /** @var string */
    protected $sheet;

    public static function make(string $collection, string $sheet): self
    {
        return (new static("Sheet [{$sheet}] in collection [{$collection}] does not exist."))->setCollection($collection)->setSheet($sheet);
    }

    public function getCollection(): string
    {
        return $this->collection;
    }

    public function setCollection(string $collection): self
    {
        $this->collection = $collection;

        return $this;
    }

    public function getSheet(): string
    {
        return $this->sheet;
    }

    public function setSheet(string $sheet): self
    {
        $this->sheet = $sheet;

        return $this;
    }

    public function getSolution(): Solution
    {
        return new AddSheetToCollectionSolution($this->getCollection(), $this->getSheet());
    }
}
