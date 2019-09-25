<?php

namespace Astrotomic\Stancy\Tests\Models;

use Astrotomic\Stancy\Tests\PageDatas\HomePageData;
use Astrotomic\Stancy\Tests\PageDatas\ParseArrayPageData;
use Astrotomic\Stancy\Tests\TestCase;
use Carbon\Carbon;
use Illuminate\Support\HtmlString;
use Spatie\DataTransferObject\DataTransferObjectError;

final class PageDataTest extends TestCase
{
    /** @test */
    public function it_throws_exception_if_data_is_missing(): void
    {
        static::expectException(DataTransferObjectError::class);

        HomePageData::make([]);
    }

    /** @test */
    public function it_throws_exception_if_data_type_does_not_match(): void
    {
        static::expectException(DataTransferObjectError::class);

        HomePageData::make([
            'contents' => 'hello world',
            'slug' => 'home',
        ]);
    }

    /** @test */
    public function it_returns_instance_if_data_is_valid(): void
    {
        $page = HomePageData::make([
            'contents' => new HtmlString('hello world'),
            'slug' => 'home',
        ]);

        static::assertInstanceOf(HomePageData::class, $page);
    }

    /** @test */
    public function it_parses_all_values_to_scalar_types(): void
    {
        Carbon::setTestNow('2019-09-25 11:53:14');

        $page = ParseArrayPageData::make([
            'contents' => new HtmlString('hello world'),
            'slug' => 'home',
            'date' => Carbon::now(),
            'array' => [
                'contents' => new HtmlString('hello world'),
                'slug' => 'home',
                'date' => Carbon::now(),
            ],
            'collection' => collect([
                ['id' => 1],
                ['id' => 2],
            ]),
        ]);

        static::assertInstanceOf(ParseArrayPageData::class, $page);
        static::assertEquals([
            'contents' => 'hello world',
            'slug' => 'home',
            'date' => '2019-09-25T11:53:14+00:00',
            'array' => [
                'contents' => 'hello world',
                'slug' => 'home',
                'date' => '2019-09-25T11:53:14+00:00',
            ],
            'collection' => [
                ['id' => 1],
                ['id' => 2],
            ],
        ], $page->toArray());
    }
}
