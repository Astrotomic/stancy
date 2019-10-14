<?php

namespace Astrotomic\Stancy\Tests\Factories;

use Astrotomic\Stancy\Contracts\Page as PageContract;
use Astrotomic\Stancy\Facades\ExportFactory as ExportFactoryFacade;
use Astrotomic\Stancy\Facades\PageFactory;
use Astrotomic\Stancy\Factories\ExportFactory;
use Astrotomic\Stancy\Tests\TestCase;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Spatie\Export\Exporter;
use Spatie\Snapshots\MatchesSnapshots;

final class ExportFactoryTest extends TestCase
{
    use MatchesSnapshots;

    /** @test */
    public function it_can_resolve_instance(): void
    {
        static::assertInstanceOf(ExportFactory::class, $this->getExportFactory());
    }

    /** @test */
    public function it_can_use_facade(): void
    {
        ExportFactoryFacade::shouldReceive('addSheetList', 'addSheetCollectionName', 'addFeeds');

        ExportFactoryFacade::addSheetCollectionName('blog');
        ExportFactoryFacade::addSheetList(['blog:first-post', 'blog:second-post']);
        ExportFactoryFacade::addFeeds();
    }

    /** @test */
    public function it_can_export_feeds(): void
    {
        Carbon::setTestNow('2019-09-25 11:53:14');

        Route::feeds();

        $this->getExportFactory()->addFeeds();

        $this->app->make(Exporter::class)->export();

        $filePath = __DIR__.'/../export/feed/blog.atom/index.html';

        static::assertFileExists($filePath);
        static::assertMatchesXmlSnapshot(file_get_contents($filePath));
    }

    /** @test */
    public function it_can_export_pages(): void
    {
        Route::get('/', function(): PageContract {
            return PageFactory::makeFromSheetName('content', 'yamlFrontMatterPredefined');
        });

        $this->getExportFactory()->addSheetList(['content:yamlFrontMatterPredefined']);

        $this->app->make(Exporter::class)->export();

        $filePath = __DIR__.'/../export/index.html';

        static::assertFileExists($filePath);
        // https://github.com/spatie/phpunit-snapshot-assertions/pull/76
        // static::assertMatchesHtmlSnapshot(file_get_contents($filePath));
        static::assertEquals('<h1>hello world</h1>', trim(file_get_contents($filePath)));
    }

    /** @test */
    public function it_can_export_a_collection(): void
    {
        Route::get('/{slug}', function(string $slug): PageContract {
            return PageFactory::makeFromSheetName('blog', $slug);
        });

        $this->getExportFactory()->addSheetCollectionName('blog');

        $this->app->make(Exporter::class)->export();

        $filePath1 = __DIR__.'/../export/first-post/index.html';
        $filePath2 = __DIR__.'/../export/second-post/index.html';

        static::assertFileExists($filePath1);
        // https://github.com/spatie/phpunit-snapshot-assertions/pull/76
        // static::assertMatchesHtmlSnapshot(file_get_contents($filePath1));
        static::assertEquals('<h1>first post</h1>', trim(file_get_contents($filePath1)));

        static::assertFileExists($filePath2);
        // https://github.com/spatie/phpunit-snapshot-assertions/pull/76
        // static::assertMatchesHtmlSnapshot(file_get_contents($filePath2));
        static::assertEquals('<h1>second post</h1>', trim(file_get_contents($filePath2)));
    }
}
