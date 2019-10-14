<?php

namespace Astrotomic\Stancy\Factories;

use Astrotomic\Stancy\Contracts\Page as PageContract;
use Astrotomic\Stancy\Contracts\PageFactory as PageFactoryContract;
use Astrotomic\Stancy\Contracts\SitemapFactory as SitemapFactoryContract;
use Astrotomic\Stancy\Traits\ConvertsSheetToPage;
use Illuminate\Support\Str;
use Spatie\Sheets\Facades\Sheets;
use Spatie\Sitemap\Sitemap;

class SitemapFactory implements SitemapFactoryContract
{
    use ConvertsSheetToPage;

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
}
