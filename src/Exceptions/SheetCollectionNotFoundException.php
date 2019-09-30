<?php

namespace Astrotomic\Stancy\Exceptions;

use Facade\IgnitionContracts\BaseSolution;
use Facade\IgnitionContracts\ProvidesSolution;
use Facade\IgnitionContracts\Solution;
use RuntimeException;

class SheetCollectionNotFoundException extends RuntimeException implements ProvidesSolution
{
    /** @var string */
    protected $collection;

    public static function make(string $collection, ?RuntimeException $exception = null): self
    {
        return (new static("Sheet collection [{$collection}] does not exist.", 0, $exception))->setCollection($collection);
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

    public function getSolution(): Solution
    {
        return BaseSolution::create('The sheet collection is missing')
            ->setSolutionDescription("Add `{$this->getCollection()}` collection to your spatie/sheets configuration at `config/sheets.php`.\n```php\nreturn [\n// ...\n'collections' => ['{$this->getCollection()}'],\n// ...\n```")
            ->setDocumentationLinks([
                'Read spatie/sheets "Creating your first collection" documentation' => 'https://github.com/spatie/sheets#creating-your-first-collection',
            ]);
    }
}
