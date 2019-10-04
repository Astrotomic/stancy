<?php

namespace Astrotomic\Stancy\Factories;

use Astrotomic\Stancy\Contracts\PageFactory as PageFactoryContract;
use Astrotomic\Stancy\Contracts\SitemapFactory as SitemapFactoryContract;
use Spatie\Sheets\Facades\Sheets;
use Spatie\Sheets\Repository as SheetRepository;
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

    public function makeFromSheetCollection(SheetRepository $collection): Sitemap
    {
        $sitemap = Sitemap::create();

        $collection->all()->each(function (Sheet $sheet) use ($sitemap) {
            $sitemap->add($this->pageFactory->makeFromSheet($sheet)->toSitemapItem());
        });

        return $sitemap;
    }

    public function makeFromSheetCollectionName(string $name): Sitemap
    {
        $collection = Sheets::collection($name);

        return $this->makeFromSheetCollection($collection);
    }
}
