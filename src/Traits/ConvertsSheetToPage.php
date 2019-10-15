<?php

namespace Astrotomic\Stancy\Traits;

use Astrotomic\Stancy\Contracts\Page as PageContract;
use Astrotomic\Stancy\Contracts\PageFactory as PageFactoryContract;
use Spatie\Sheets\Sheet;

/**
 * @internal
 */
trait ConvertsSheetToPage
{
    /** @var PageFactoryContract */
    protected $pageFactory;

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
