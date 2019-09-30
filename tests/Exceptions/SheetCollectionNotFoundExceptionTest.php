<?php

namespace Astrotomic\Stancy\Tests\Exceptions;

use Astrotomic\Stancy\Exceptions\SheetCollectionNotFoundException;
use Astrotomic\Stancy\Tests\TestCase;
use Facade\IgnitionContracts\BaseSolution;

final class SheetCollectionNotFoundExceptionTest extends TestCase
{
    /** @test */
    public function it_returns_the_given_collection(): void
    {
        $exception = SheetCollectionNotFoundException::make('foobar');

        static::assertEquals('foobar', $exception->getCollection());
    }

    /** @test */
    public function it_returns_a_solution(): void
    {
        $exception = SheetCollectionNotFoundException::make('foobar');
        $solution = $exception->getSolution();

        static::assertInstanceOf(BaseSolution::class, $solution);
        static::assertEquals('The sheet collection is missing', $solution->getSolutionTitle());
        static::assertEquals("Add `foobar` collection to your spatie/sheets configuration at `config/sheets.php`.\n```php\nreturn [\n// ...\n'collections' => ['foobar'],\n// ...\n```", $solution->getSolutionDescription());
        static::assertEquals([
            'Read spatie/sheets "Creating your first collection" documentation' => 'https://github.com/spatie/sheets#creating-your-first-collection',
        ], $solution->getDocumentationLinks());
    }
}
