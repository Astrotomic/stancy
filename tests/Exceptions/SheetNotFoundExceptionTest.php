<?php

namespace Astrotomic\Stancy\Tests\Exceptions;

use Astrotomic\Stancy\Exceptions\SheetNotFoundException;
use Astrotomic\Stancy\Solutions\AddSheetToCollectionSolution;
use Astrotomic\Stancy\Tests\TestCase;
use Illuminate\Support\Facades\Storage;

final class SheetNotFoundExceptionTest extends TestCase
{
    /** @test */
    public function it_returns_the_given_collection(): void
    {
        $exception = SheetNotFoundException::make('content', 'foobar');

        static::assertEquals('content', $exception->getCollection());
        static::assertEquals('foobar', $exception->getSheet());
    }

    /** @test */
    public function it_returns_a_solution(): void
    {
        $exception = SheetNotFoundException::make('content', 'foobar');
        /** @var AddSheetToCollectionSolution $solution */
        $solution = $exception->getSolution();

        static::assertInstanceOf(AddSheetToCollectionSolution::class, $solution);
        static::assertEquals('The sheet is missing', $solution->getSolutionTitle());
        static::assertEquals('Add sheet `foobar` to collection `content`.', $solution->getSolutionDescription());
        static::assertEquals('Add sheet file', $solution->getRunButtonText());
        static::assertEquals('Pressing the button below will try to add the sheet file to the collection filesystem.', $solution->getSolutionActionDescription());
        static::assertEquals([], $solution->getRunParameters());
        static::assertEquals([], $solution->getDocumentationLinks());
    }

    /** @test */
    public function it_can_run_the_solution(): void
    {
        Storage::disk('content')->delete('foobar.md');
        $exception = SheetNotFoundException::make('content', 'foobar');
        /** @var AddSheetToCollectionSolution $solution */
        $solution = $exception->getSolution();

        static::assertInstanceOf(AddSheetToCollectionSolution::class, $solution);

        static::assertTrue($solution->run());
        static::assertTrue(Storage::disk('content')->exists('foobar.md'));
        Storage::disk('content')->delete('foobar.md');
    }

    /** @test */
    public function it_can_run_the_solution_with_subdirectory_sheet(): void
    {
        Storage::disk('content')->deleteDirectory('sub');
        $exception = SheetNotFoundException::make('content', 'sub/foobar');
        /** @var AddSheetToCollectionSolution $solution */
        $solution = $exception->getSolution();

        static::assertInstanceOf(AddSheetToCollectionSolution::class, $solution);

        static::assertTrue($solution->run());
        static::assertTrue(Storage::disk('content')->exists('sub/foobar.md'));
        Storage::disk('content')->deleteDirectory('sub');
    }
}
