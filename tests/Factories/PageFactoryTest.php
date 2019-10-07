<?php

namespace Astrotomic\Stancy\Tests\Factories;

use Astrotomic\Stancy\Exceptions\SheetCollectionNotFoundException;
use Astrotomic\Stancy\Exceptions\SheetNotFoundException;
use Astrotomic\Stancy\Facades\PageFactory as PageFactoryFacade;
use Astrotomic\Stancy\Factories\PageFactory;
use Astrotomic\Stancy\Models\Page;
use Astrotomic\Stancy\Tests\TestCase;
use Spatie\Sheets\Facades\Sheets;

final class PageFactoryTest extends TestCase
{
    /** @test */
    public function it_can_resolve_instance(): void
    {
        static::assertInstanceOf(PageFactory::class, $this->getPageFactory());
    }

    /** @test */
    public function it_can_use_facade(): void
    {
        PageFactoryFacade::shouldReceive('make', 'makeFromSheet', 'makeFromSheetName');

        PageFactoryFacade::make();
        PageFactoryFacade::makeFromSheet(Sheets::collection('content')->get('home'));
        PageFactoryFacade::makeFromSheetName('content', 'home');
    }

    /** @test */
    public function it_can_make_page(): void
    {
        $page = $this->getPageFactory()->make();

        static::assertInstanceOf(Page::class, $page);
    }

    /** @test */
    public function it_can_make_page_from_sheet(): void
    {
        $page = $this->getPageFactory()->makeFromSheetName('content', 'home');

        static::assertInstanceOf(Page::class, $page);
    }

    /** @test */
    public function it_throws_exception_if_sheet_collection_does_not_exist(): void
    {
        static::expectException(SheetCollectionNotFoundException::class);
        static::expectExceptionMessage('Sheet collection [foobar] does not exist.');

        $this->getPageFactory()->makeFromSheetName('foobar', 'undefined_sheet');
    }

    /** @test */
    public function it_throws_exception_if_sheet_does_not_exist(): void
    {
        static::expectException(SheetNotFoundException::class);
        static::expectExceptionMessage('Sheet [undefined_sheet] in collection [content] does not exist.');

        $this->getPageFactory()->makeFromSheetName('content', 'undefined_sheet');
    }
}
