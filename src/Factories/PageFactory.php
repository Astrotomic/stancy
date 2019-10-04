<?php

namespace Astrotomic\Stancy\Factories;

use Astrotomic\Stancy\Contracts\Page as PageContract;
use Astrotomic\Stancy\Contracts\PageFactory as PageFactoryContract;
use Astrotomic\Stancy\Exceptions\SheetCollectionNotFoundException;
use Astrotomic\Stancy\Exceptions\SheetNotFoundException;
use RuntimeException;
use Spatie\Sheets\Facades\Sheets;
use Spatie\Sheets\Sheet;

class PageFactory implements PageFactoryContract
{
    public function make(array $data = [], ?string $page = null): PageContract
    {
        return app(PageContract::class, [
            'data' => $data,
            'page' => $page,
        ]);
    }

    public function makeFromSheet(Sheet $sheet, ?string $page = null): PageContract
    {
        return static::make($sheet->toArray(), $page);
    }

    public function makeFromSheetName(string $collection, string $name, ?string $page = null): PageContract
    {
        try {
            $sheet = Sheets::collection($collection)->get($name);
        } catch (RuntimeException $exception) {
            throw SheetCollectionNotFoundException::make($collection, $exception);
        }

        if ($sheet === null) {
            throw SheetNotFoundException::make($collection, $name);
        }

        return static::makeFromSheet($sheet, $page);
    }
}
