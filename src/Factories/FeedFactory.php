<?php

namespace Astrotomic\Stancy\Factories;

use Astrotomic\Stancy\Contracts\FeedFactory as FeedFactoryContract;
use Astrotomic\Stancy\Contracts\PageFactory as PageFactoryContract;
use Spatie\Sheets\Facades\Sheets;
use Spatie\Sheets\Repository as SheetRepository;
use Spatie\Sheets\Sheet;

class FeedFactory implements FeedFactoryContract
{
    /** @var PageFactoryContract */
    protected $pageFactory;

    public function __construct(PageFactoryContract $pageFactory)
    {
        $this->pageFactory = $pageFactory;
    }

    public function makeFromSheetCollection(SheetRepository $collection): array
    {
        return $collection->all()->map(function (Sheet $sheet) {
            return $this->pageFactory->makeFromSheet($sheet);
        })->all();
    }

    public function makeFromSheetCollectionName(string $name): array
    {
        $collection = Sheets::collection($name);

        return $this->makeFromSheetCollection($collection);
    }
}
