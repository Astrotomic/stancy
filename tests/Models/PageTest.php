<?php

namespace Astrotomic\Stancy\Tests\Models;

use Astrotomic\Stancy\Models\Page;
use Astrotomic\Stancy\Tests\PageDatas\HomePageData;
use Astrotomic\Stancy\Tests\TestCase;
use Exception;
use Illuminate\Contracts\View\View;
use Spatie\DataTransferObject\DataTransferObjectError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class PageTest extends TestCase
{
    /** @test */
    public function it_can_resolve_instance(): void
    {
        $page = $this->app->make(Page::class);

        static::assertInstanceOf(Page::class, $page);
    }

    /** @test */
    public function it_can_make_instance(): void
    {
        $page = Page::make();

        static::assertInstanceOf(Page::class, $page);
    }

    /** @test */
    public function it_can_make_instance_from_sheet(): void
    {
        $page = Page::makeFromSheet('content', 'home');

        static::assertInstanceOf(Page::class, $page);
    }

    /** @test */
    public function it_can_render_from_sheet(): void
    {
        $page = Page::makeFromSheet('content', 'home')->view('pages.home');

        static::assertInstanceOf(Page::class, $page);
        static::assertInstanceOf(View::class, $page->render());
        static::assertEquals('<h1>hello world</h1>', trim($page->toHtml()));
        static::assertInstanceOf(Response::class, $page->toResponse($this->getRequest()));
    }

    /** @test */
    public function it_can_render_from_sheet_with_validated_data(): void
    {
        $page = Page::makeFromSheet('content', 'home')->view('pages.home')->page(HomePageData::class);

        static::assertInstanceOf(Page::class, $page);
        static::assertInstanceOf(View::class, $page->render());
        static::assertEquals('<h1>hello world</h1>', trim($page->toHtml()));
        static::assertInstanceOf(Response::class, $page->toResponse($this->getRequest()));
    }

    /** @test */
    public function it_returns_data_as_json_if_wanted(): void
    {
        $page = Page::makeFromSheet('content', 'home')->page(HomePageData::class);

        $response = $page->toResponse($this->getRequest('/', ['Accept' => 'application/json']));

        static::assertInstanceOf(JsonResponse::class, $response);
    }

    /** @test */
    public function it_throws_exception_if_sheet_does_not_exist(): void
    {
        static::expectException(Exception::class);
        static::expectExceptionMessage('No sheet found in collection [content] with name [undefined_sheet].');

        Page::makeFromSheet('content', 'undefined_sheet');
    }

    /** @test */
    public function it_throws_exception_if_page_data_class_does_not_exist(): void
    {
        static::expectException(Exception::class);
        static::expectExceptionMessage('The page data class [Strange\Unexisting\Class] has to extend Astrotomic\Stancy\Models\PageData.');

        Page::make([], 'Strange\Unexisting\Class');
    }

    /** @test */
    public function it_throws_exception_if_page_data_class_does_not_extend_page_data(): void
    {
        static::expectException(Exception::class);
        static::expectExceptionMessage('The page data class [Astrotomic\Stancy\Models\Page] has to extend Astrotomic\Stancy\Models\PageData.');

        Page::make([], Page::class);
    }

    /** @test */
    public function it_throws_exception_if_rendered_without_view(): void
    {
        static::expectException(Exception::class);
        static::expectExceptionMessage('You have to define a view before the page can render.');

        $page = Page::make();

        static::assertInstanceOf(Page::class, $page);

        $page->render();
    }

    /** @test */
    public function it_throws_exception_if_data_is_not_valid(): void
    {
        static::expectException(DataTransferObjectError::class);

        Page::make()->page(HomePageData::class);
    }
}
