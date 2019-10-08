<?php

namespace Astrotomic\Stancy\Tests\Factories;

use Astrotomic\Stancy\Facades\SitemapFactory as SitemapFactoryFacade;
use Astrotomic\Stancy\Factories\SitemapFactory;
use Astrotomic\Stancy\Tests\TestCase;
use Carbon\Carbon;
use Spatie\Snapshots\MatchesSnapshots;

final class SitemapFactoryTest extends TestCase
{
    use MatchesSnapshots;

    /** @test */
    public function it_can_resolve_instance(): void
    {
        static::assertInstanceOf(SitemapFactory::class, $this->getSitemapFactory());
    }

    /** @test */
    public function it_can_use_facade(): void
    {
        SitemapFactoryFacade::shouldReceive('makeFromSheetList', 'makeFromSheetCollectionName');

        SitemapFactoryFacade::makeFromSheetCollectionName('blog');
        SitemapFactoryFacade::makeFromSheetList(['blog:first-post', 'blog:second-post']);
    }

    /** @test */
    public function it_can_make_sitemap_from_collection_name(): void
    {
        Carbon::setTestNow('2019-09-25 11:53:14');

        $sitemap = $this->getSitemapFactory()->makeFromSheetCollectionName('blog');

        static::assertMatchesXmlSnapshot($sitemap->render());
    }

    /** @test */
    public function it_can_make_sitemap_from_list_of_sheets(): void
    {
        Carbon::setTestNow('2019-09-25 11:53:14');

        $sitemap = $this->getSitemapFactory()->makeFromSheetList(['blog:first-post', 'blog:second-post']);

        static::assertMatchesXmlSnapshot($sitemap->render());
    }
}
