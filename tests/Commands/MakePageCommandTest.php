<?php

namespace Astrotomic\Stancy\Tests\Commands;

use Astrotomic\Stancy\Tests\TestCase;
use Spatie\Sheets\ContentParsers\JsonParser;
use Spatie\Sheets\ContentParsers\YamlParser;
use Spatie\Snapshots\MatchesSnapshots;

final class MakePageCommandTest extends TestCase
{
    use MatchesSnapshots;

    /** @test */
    public function it_can_create_page_data_class(): void
    {
        $this->artisan('make:page', [
            'name' => 'Test',
        ]);

        $pageDataPath = app_path('Pages/Test.php');
        static::assertFileExists($pageDataPath);
        static::assertMatchesFileSnapshot($pageDataPath);
        unlink($pageDataPath);
    }

    /** @test */
    public function it_can_create_page_data_class_in_sub_namespace(): void
    {
        $this->artisan('make:page', [
            'name' => 'Sub/Test',
        ]);

        $pageDataPath = app_path('Pages/Sub/Test.php');
        static::assertFileExists($pageDataPath);
        static::assertMatchesFileSnapshot($pageDataPath);
        unlink($pageDataPath);
    }

    /** @test */
    public function it_can_create_page_with_markdown(): void
    {
        $this->artisan('make:page', [
            'name' => 'Test',
            '--collection' => 'content',
        ]);

        $pageDataPath = app_path('Pages/Test.php');
        static::assertFileExists($pageDataPath);
        static::assertMatchesFileSnapshot($pageDataPath);

        $pageContentPath = __DIR__.'/../resources/content/test.md';
        static::assertFileExists($pageContentPath);
        static::assertMatchesFileSnapshot($pageContentPath);

        unlink($pageDataPath);
        unlink($pageContentPath);
    }

    /** @test */
    public function it_can_create_page_with_json(): void
    {
        $this->app['config']->set('filesystems.disks.data', [
            'driver' => 'local',
            'root' => realpath(__DIR__.'/../resources/content/data'),
        ]);
        $this->app['config']->set('sheets.collections.data', [
            'content_parser' => JsonParser::class,
            'extension' => 'json',
        ]);

        $this->artisan('make:page', [
            'name' => 'Test',
            '--collection' => 'data',
        ]);

        $pageDataPath = app_path('Pages/Test.php');
        static::assertFileExists($pageDataPath);
        static::assertMatchesFileSnapshot($pageDataPath);

        $pageContentPath = __DIR__.'/../resources/content/data/test.json';
        static::assertFileExists($pageContentPath);
        static::assertMatchesFileSnapshot($pageContentPath);

        unlink($pageDataPath);
        unlink($pageContentPath);
    }

    /** @test */
    public function it_can_create_page_with_yaml(): void
    {
        $this->app['config']->set('filesystems.disks.data', [
            'driver' => 'local',
            'root' => realpath(__DIR__.'/../resources/content/data'),
        ]);
        $this->app['config']->set('sheets.collections.data', [
            'content_parser' => YamlParser::class,
            'extension' => 'yaml',
        ]);

        $this->artisan('make:page', [
            'name' => 'Test',
            '--collection' => 'data',
        ]);

        $pageDataPath = app_path('Pages/Test.php');
        static::assertFileExists($pageDataPath);
        static::assertMatchesFileSnapshot($pageDataPath);

        $pageContentPath = __DIR__.'/../resources/content/data/test.yaml';
        static::assertFileExists($pageContentPath);
        static::assertMatchesFileSnapshot($pageContentPath);

        unlink($pageDataPath);
        unlink($pageContentPath);
    }

    /** @test */
    public function it_can_create_page_with_unknown_extension(): void
    {
        $this->app['config']->set('filesystems.disks.data', [
            'driver' => 'local',
            'root' => realpath(__DIR__.'/../resources/content/data'),
        ]);
        $this->app['config']->set('sheets.collections.data', [
            'extension' => 'txt',
        ]);

        $this->artisan('make:page', [
            'name' => 'Test',
            '--collection' => 'data',
        ]);

        $pageDataPath = app_path('Pages/Test.php');
        static::assertFileExists($pageDataPath);
        static::assertMatchesFileSnapshot($pageDataPath);

        $pageContentPath = __DIR__.'/../resources/content/data/test.txt';
        static::assertFileExists($pageContentPath);
        static::assertMatchesFileSnapshot($pageContentPath);

        unlink($pageDataPath);
        unlink($pageContentPath);
    }

    /** @test */
    public function it_does_not_override_existing_page(): void
    {
        $this->artisan('make:page', [
            'name' => 'Test',
            '--collection' => 'content',
        ]);

        $pageDataPath = app_path('Pages/Test.php');
        static::assertFileExists($pageDataPath);
        static::assertMatchesFileSnapshot($pageDataPath);

        $pageContentPath = __DIR__.'/../resources/content/test.md';
        static::assertFileExists($pageContentPath);
        static::assertMatchesFileSnapshot($pageContentPath);

        $this->artisan('make:page', [
            'name' => 'Test',
            '--collection' => 'content',
        ])->expectsOutput('the sheet `test.md` already exists');

        static::assertFileExists($pageDataPath);
        static::assertMatchesFileSnapshot($pageDataPath);

        static::assertFileExists($pageContentPath);
        static::assertMatchesFileSnapshot($pageContentPath);

        unlink($pageDataPath);
        unlink($pageContentPath);
    }
}
