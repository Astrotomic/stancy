<?php

namespace Astrotomic\Stancy\Tests\Models;

use Astrotomic\Stancy\Models\Page;
use Astrotomic\Stancy\Tests\PageDatas\FeedablePageData;
use Astrotomic\Stancy\Tests\PageDatas\HomePageData;
use Astrotomic\Stancy\Tests\TestCase;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Support\HtmlString;
use RuntimeException;
use Spatie\DataTransferObject\DataTransferObjectError;
use Spatie\Feed\FeedItem;
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
    public function it_can_render_from_sheet(): void
    {
        $page = $this->getPageFactory()->makeFromSheet('content', 'home')->view('pages.home');

        static::assertInstanceOf(Page::class, $page);
        static::assertInstanceOf(View::class, $page->render());
        static::assertEquals('<h1>hello world</h1>', trim($page->toHtml()));
        static::assertInstanceOf(Response::class, $page->toResponse($this->getRequest()));
    }

    /** @test */
    public function it_can_render_from_sheet_with_yaml_front_matter_predefined_variables(): void
    {
        $page = $this->getPageFactory()->makeFromSheet('content', 'yamlFrontMatterPredefined');

        static::assertInstanceOf(Page::class, $page);
        static::assertInstanceOf(View::class, $page->render());
        static::assertEquals('<h1>hello world</h1>', trim($page->toHtml()));
        static::assertInstanceOf(Response::class, $page->toResponse($this->getRequest()));
    }

    /** @test */
    public function it_can_render_from_sheet_with_validated_data(): void
    {
        $page = $this->getPageFactory()->makeFromSheet('content', 'home')->view('pages.home')->page(HomePageData::class);

        static::assertInstanceOf(Page::class, $page);
        static::assertInstanceOf(View::class, $page->render());
        static::assertEquals('<h1>hello world</h1>', trim($page->toHtml()));
        static::assertInstanceOf(Response::class, $page->toResponse($this->getRequest()));
    }

    /** @test */
    public function it_can_return_data_as_array(): void
    {
        $page = $this->getPageFactory()->makeFromSheet('content', 'home')->page(HomePageData::class);

        static::assertEquals([
            'contents' => new HtmlString("<h1>hello world</h1>\n"),
            'slug' => 'home',
        ], $page->toArray());
    }

    /** @test */
    public function it_can_return_data_as_json(): void
    {
        $page = $this->getPageFactory()->makeFromSheet('content', 'home')->page(HomePageData::class);

        static::assertEquals('{"contents":"<h1>hello world<\/h1>\n","slug":"home"}', $page->toJson());
    }

    /** @test */
    public function it_responses_data_as_json_if_wanted(): void
    {
        $page = $this->getPageFactory()->makeFromSheet('content', 'home')->page(HomePageData::class);

        $response = $page->toResponse($this->getRequest('/', ['Accept' => 'application/json']));

        static::assertInstanceOf(JsonResponse::class, $response);
        static::assertEquals('{"contents":"<h1>hello world<\/h1>\n","slug":"home"}', $response->getContent());
    }

    /** @test */
    public function it_can_load_child_sheets(): void
    {
        $page = $this->getPageFactory()->makeFromSheet('content', 'blog');

        static::assertEquals([
            'contents' => '',
            'slug' => 'blog',
            'home' => [
                'contents' => new HtmlString("<h1>hello world</h1>\n"),
                'slug' => 'home',
            ],
            'posts' => [
                [
                    'contents' => new HtmlString("<h1>first post</h1>\n"),
                    'slug' => 'first-post',
                ],
                [
                    'contents' => new HtmlString("<h1>second post</h1>\n"),
                    'slug' => 'second-post',
                ],
            ],
        ], $page->toArray());
    }

    /** @test */
    public function it_throws_exception_if_page_data_class_does_not_exist(): void
    {
        static::expectException(Exception::class);
        static::expectExceptionMessage('The page data class [Strange\Unexisting\Class] has to extend Astrotomic\Stancy\Models\PageData.');

        $this->getPageFactory()->make([], 'Strange\Unexisting\Class');
    }

    /** @test */
    public function it_throws_exception_if_page_data_class_does_not_extend_page_data(): void
    {
        static::expectException(Exception::class);
        static::expectExceptionMessage('The page data class [Astrotomic\Stancy\Models\Page] has to extend Astrotomic\Stancy\Models\PageData.');

        $this->getPageFactory()->make([], Page::class);
    }

    /** @test */
    public function it_throws_exception_if_rendered_without_view(): void
    {
        static::expectException(Exception::class);
        static::expectExceptionMessage('You have to define a view before the page can render.');

        $page = $this->getPageFactory()->make();

        static::assertInstanceOf(Page::class, $page);

        $page->render();
    }

    /** @test */
    public function it_throws_exception_if_data_is_not_valid(): void
    {
        static::expectException(DataTransferObjectError::class);

        $this->getPageFactory()->make()->page(HomePageData::class);
    }

    /** @test */
    public function it_throws_exception_if_data_is_not_valid_with_yaml_front_matter_predefined_variables(): void
    {
        static::expectException(DataTransferObjectError::class);

        $this->getPageFactory()->makeFromSheet('content', 'yamlFrontMatterPredefinedInvalid');
    }

    /** @test */
    public function it_throws_exception_on_feed_item_transformation_if_data_is_not_page_data_class(): void
    {
        static::expectException(Exception::class);
        static::expectExceptionMessage('The page data has to extend Astrotomic\Stancy\Models\PageData to allow transformation to Spatie\Feed\FeedItem.');

        $this->getPageFactory()->make()->toFeedItem();
    }

    /** @test */
    public function it_throws_exception_if_sheets_array_is_not_associative(): void
    {
        static::expectException(RuntimeException::class);
        static::expectExceptionMessage('The [_sheets] data has to be an associative array.');

        $this->getPageFactory()->makeFromSheet('content', 'yamlFrontMatterChildSheetsInvalid');
    }

    /** @test */
    public function it_returns_feed_item_if_page_data_supports_it(): void
    {
        $feedItem = $this->getPageFactory()->make()->page(FeedablePageData::class)->toFeedItem();

        static::assertInstanceOf(FeedItem::class, $feedItem);
        $feedItem->validate();
    }
}
