<?php

namespace Astrotomic\Stancy\Factories;

use Astrotomic\Stancy\Contracts\Page as PageContract;
use Astrotomic\Stancy\Contracts\PageFactory as PageFactoryContract;
use Astrotomic\Stancy\Contracts\SitemapFactory as SitemapFactoryContract;
use Illuminate\Support\Str;
use Spatie\Sheets\Facades\Sheets;
use Spatie\Sheets\Sheet;
use Spatie\Sitemap\Sitemap;

class SitemapFactory implements SitemapFactoryContract
{
    /** @var PageFactoryContract */
    protected $pageFactory;

    public function __construct(PageFactoryContract $pageFactory)
    {
        $this->pageFactory = $pageFactory;
    }

    /**
     * @param string[] $list
     *
     * @return Sitemap
     */
    public function makeFromSheetList(array $list): Sitemap
    {
        $pages = [];

        foreach ($list as $entry) {
            if (Str::contains($entry, ':')) {
                [$collection, $path] = explode(':', $entry);

                $pages[] = $this->sheetToPage(Sheets::collection($collection)->get($path));

                continue;
            }

            $pages = array_merge($pages, $this->sheetsToPages(Sheets::collection($entry)->all()->all()));
        }

        return $this->makeFromPages($pages);
    }

    public function makeFromSheetCollectionName(string $name): Sitemap
    {
        return $this->makeFromSheetList([$name]);
    }

    /**
     * @param PageContract[] $pages
     *
     * @return Sitemap
     */
    protected function makeFromPages(array $pages): Sitemap
    {
        $sitemap = Sitemap::create();

        foreach ($pages as $page) {
            $sitemap->add($page->toSitemapItem());
        }

        return $sitemap;
    }

    /**
     * @param Sheet[] $sheets
     *
     * @return PageContract[]
     */
    protected function sheetsToPages(array $sheets): array
    {
        return array_map(function (Sheet $sheet): PageContract {
            return $this->sheetToPage($sheet);
        }, $sheets);
    }

    protected function sheetToPage(Sheet $sheet): PageContract
    {
        return $this->pageFactory->makeFromSheet($sheet);
    }
}
