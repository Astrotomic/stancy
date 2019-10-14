<?php

namespace Astrotomic\Stancy\Factories;

use Astrotomic\Stancy\Contracts\ExportFactory as ExportFactoryContract;
use Astrotomic\Stancy\Contracts\Page as PageContract;
use Astrotomic\Stancy\Contracts\PageFactory as PageFactoryContract;
use Astrotomic\Stancy\Traits\ConvertsSheetToPage;
use Illuminate\Contracts\Routing\UrlGenerator as UrlGeneratorContract;
use Illuminate\Support\Str;
use Spatie\Export\Exporter;
use Spatie\Sheets\Facades\Sheets;

class ExportFactory implements ExportFactoryContract
{
    use ConvertsSheetToPage;

    /** @var Exporter */
    protected $exporter;

    /** @var UrlGeneratorContract */
    protected $urlGenerator;

    public function __construct(PageFactoryContract $pageFactory, Exporter $exporter, UrlGeneratorContract $urlGenerator)
    {
        $this->pageFactory = $pageFactory;
        $this->exporter = $exporter;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param string[] $list
     *
     * @return void
     */
    public function addSheetList(array $list): void
    {
        foreach ($list as $entry) {
            if (Str::contains($entry, ':')) {
                [$collection, $path] = explode(':', $entry);

                $this->addPages([$this->sheetToPage(Sheets::collection($collection)->get($path))]);

                continue;
            }

            $this->addPages($this->sheetsToPages(Sheets::collection($entry)->all()->all()));
        }
    }

    public function addSheetCollectionName(string $name): void
    {
        $this->addSheetList([$name]);
    }

    public function addFeeds(array $except = []): void
    {
        collect(config('feed.feeds'))->except($except)->each(function (array $config): void {
            $this->exporter->paths([$config['url']]);
        });
    }

    /**
     * @param PageContract[] $pages
     *
     * @return void
     */
    protected function addPages(array $pages): void
    {
        foreach ($pages as $page) {
            $this->exporter->paths([Str::replaceFirst($this->urlGenerator->to('/'), '', $page->getUrl())]);
        }
    }
}
