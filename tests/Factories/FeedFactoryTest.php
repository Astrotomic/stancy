<?php

namespace Astrotomic\Stancy\Tests\Factories;

use Astrotomic\Stancy\Facades\FeedFactory as FeedFactoryFacade;
use Astrotomic\Stancy\Contracts\FeedFactory as FeedFactoryContract;
use Astrotomic\Stancy\Factories\FeedFactory;
use Astrotomic\Stancy\Tests\TestCase;
use Carbon\Carbon;
use Spatie\Feed\Feed;
use Spatie\Sheets\Facades\Sheets;
use Spatie\Snapshots\MatchesSnapshots;

final class FeedFactoryTest extends TestCase
{
    use MatchesSnapshots;

    /** @test */
    public function it_can_resolve_instance(): void
    {
        static::assertInstanceOf(FeedFactory::class, $this->getFeedFactory());
    }

    /** @test */
    public function it_can_use_facade(): void
    {
        FeedFactoryFacade::shouldReceive('makeFromSheetCollection', 'makeFromSheetCollectionName');

        FeedFactoryFacade::makeFromSheetCollection(Sheets::collection('blog'));
        FeedFactoryFacade::makeFromSheetCollectionName('blog');
    }

    /** @test */
    public function it_can_make_feed_from_collection(): void
    {
        Carbon::setTestNow('2019-09-25 11:53:14');

        $request = $this->getRequest('/feed/blog');

        $feed = new Feed(
            'test blog feed',
            $request->getUri(),
            [FeedFactoryContract::class.'@makeFromSheetCollectionName', 'blog'],
            'feed::feed',
            '',
            ''
        );

        static::assertMatchesXmlSnapshot($feed->toResponse($request)->getContent());
    }
}
