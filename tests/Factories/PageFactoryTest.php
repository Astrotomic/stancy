<?php

namespace Astrotomic\Stancy\Tests\Factories;

use Astrotomic\Stancy\Exceptions\SheetCollectionNotFoundException;
use Astrotomic\Stancy\Exceptions\SheetNotFoundException;
use Astrotomic\Stancy\Factories\PageFactory;
use Astrotomic\Stancy\Models\Page;
use Astrotomic\Stancy\Tests\PageDatas\FeedablePageData;
use Astrotomic\Stancy\Tests\PageDatas\HomePageData;
use Astrotomic\Stancy\Tests\TestCase;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Support\HtmlString;
use Spatie\DataTransferObject\DataTransferObjectError;
use Spatie\Feed\FeedItem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

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
