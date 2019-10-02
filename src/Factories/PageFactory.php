<?php

namespace Astrotomic\Stancy\Factories;

use Astrotomic\Stancy\Contracts\PageFactory as PageFactoryContract;
use Astrotomic\Stancy\Exceptions\SheetCollectionNotFoundException;
use Astrotomic\Stancy\Exceptions\SheetNotFoundException;
use Astrotomic\Stancy\Models\Page;
use RuntimeException;
use Spatie\Sheets\Facades\Sheets;

class PageFactory implements PageFactoryContract
{
    public function make(array $data = [], ?string $page = null): Page
    {
        return app(Page::class, [
            'data' => $data,
            'page' => $page,
        ]);
    }

    public function makeFromSheet(string $collection, string $name, ?string $page = null): Page
    {
        try {
            $sheet = Sheets::collection($collection)->get($name);
        } catch (RuntimeException $exception) {
            throw SheetCollectionNotFoundException::make($collection, $exception);
        }

        if ($sheet === null) {
            throw SheetNotFoundException::make($collection, $name);
        }

        return static::make($sheet->toArray(), $page);
    }
}
