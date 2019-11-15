<?php

namespace Astrotomic\Stancy\Tests\Factories;

use Astrotomic\Stancy\Contracts\Page as PageContract;
use Astrotomic\Stancy\Facades\ExportFactory as ExportFactoryFacade;
use Astrotomic\Stancy\Facades\PageFactory;
use Astrotomic\Stancy\Factories\ExportFactory;
use Astrotomic\Stancy\Tests\TestCase;
use Carbon\Carbon;
use Exception;
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

        $filePath = __DIR__.'/../export/feed/blog.atom';

        static::assertFileExists($filePath);
        static::assertMatchesXmlSnapshot(file_get_contents($filePath));
    }

    /** @test */
    public function it_can_export_pages(): void
    {
        Route::get('/', function (): PageContract {
            return PageFactory::makeFromSheetName('content', 'yamlFrontMatterPredefined');
        });

        $this->getExportFactory()->addSheetList(['content:yamlFrontMatterPredefined']);

        $this->app->make(Exporter::class)->export();

        $filePath = __DIR__.'/../export/index.html';

        static::assertFileExists($filePath);
        static::assertMatchesFileSnapshot($filePath);
    }

    /** @test */
    public function it_can_export_a_collection(): void
    {
        Route::get('/{slug}', function (string $slug): PageContract {
            return PageFactory::makeFromSheetName('blog', $slug);
        });

        $this->getExportFactory()->addSheetCollectionName('blog');

        $this->app->make(Exporter::class)->export();

        $filePath1 = __DIR__.'/../export/first-post/index.html';
        $filePath2 = __DIR__.'/../export/second-post/index.html';

        static::assertFileExists($filePath1);
        static::assertMatchesFileSnapshot($filePath1);

        static::assertFileExists($filePath2);
        static::assertMatchesFileSnapshot($filePath2);
    }

    /** @test */
    public function it_throws_exception_if_page_data_is_not_instance_of_routable(): void
    {
        static::expectException(Exception::class);
        static::expectExceptionMessage('The page data has to be instance of Astrotomic\Stancy\Contracts\Routable to allow access to the URL.');

        $this->getExportFactory()->addSheetList(['content:home']);

        $this->app->make(Exporter::class)->export();
    }
}
