<?php

namespace Astrotomic\Stancy\Tests\Factories;

use Astrotomic\Stancy\Exceptions\SheetCollectionNotFoundException;
use Astrotomic\Stancy\Exceptions\SheetNotFoundException;
use Astrotomic\Stancy\Factories\PageFactory;
use Astrotomic\Stancy\Models\Page;
use Astrotomic\Stancy\Tests\TestCase;

final class PageFactoryTest extends TestCase
{
    /** @test */
    public function it_can_resolve_instance(): void
    {
        static::assertInstanceOf(PageFactory::class, $this->getPageFactory());
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
        $page = $this->getPageFactory()->makeFromSheet('content', 'home');

        static::assertInstanceOf(Page::class, $page);
    }

    /** @test */
    public function it_throws_exception_if_sheet_collection_does_not_exist(): void
    {
        static::expectException(SheetCollectionNotFoundException::class);
        static::expectExceptionMessage('Sheet collection [foobar] does not exist.');

        $this->getPageFactory()->makeFromSheet('foobar', 'undefined_sheet');
    }

    /** @test */
    public function it_throws_exception_if_sheet_does_not_exist(): void
    {
        static::expectException(SheetNotFoundException::class);
        static::expectExceptionMessage('Sheet [undefined_sheet] in collection [content] does not exist.');

        $this->getPageFactory()->makeFromSheet('content', 'undefined_sheet');
    }
}
