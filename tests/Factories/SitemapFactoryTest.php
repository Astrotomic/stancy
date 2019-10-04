<?php

namespace Astrotomic\Stancy\Tests\Factories;

use Astrotomic\Stancy\Facades\SitemapFactory as SitemapFactoryFacade;
use Astrotomic\Stancy\Factories\SitemapFactory;
use Astrotomic\Stancy\Tests\TestCase;
use Carbon\Carbon;
use Spatie\Sheets\Facades\Sheets;
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
        SitemapFactoryFacade::shouldReceive('makeFromSheetCollection', 'makeFromSheetCollectionName');

        SitemapFactoryFacade::makeFromSheetCollection(Sheets::collection('blog'));
        SitemapFactoryFacade::makeFromSheetCollectionName('blog');
    }

    /** @test */
    public function it_can_make_feed_from_collection(): void
    {
        Carbon::setTestNow('2019-09-25 11:53:14');

        $sitemap = $this->getSitemapFactory()->makeFromSheetCollectionName('blog');

        static::assertMatchesXmlSnapshot($sitemap->render());
    }
}
