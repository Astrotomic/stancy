<?php

namespace Astrotomic\Stancy\Models;

use Astrotomic\Stancy\Contracts\Page as PageContract;
use Astrotomic\Stancy\Contracts\Routable;
use Exception;
use Illuminate\Contracts\View\Factory as ViewFactoryContract;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Traits\Macroable;
use RuntimeException;
use Spatie\Feed\FeedItem;
use Spatie\Sheets\Facades\Sheets;
use Spatie\Sheets\Sheet;
use Spatie\Sitemap\Tags\Tag;
use Symfony\Component\HttpFoundation\Response;

class Page implements PageContract
{
    use Macroable;

    /** @var string|null */
    protected $view;

    /** @var string|null */
    protected $page;

    /** @var PageData|array */
    protected $data = [];

    /** @var ViewFactoryContract */
    protected $viewFactory;

    public function __construct(ViewFactoryContract $viewFactory, array $data = [], ?string $page = null)
    {
        $this->viewFactory = $viewFactory;

        $this->view($data['_view'] ?? null);

        $page = $page ?? $data['_pageData'] ?? null;

        $data = array_merge($data, $this->getSheetData($data['_sheets'] ?? []));

        $this->data($data);
        $this->page($page);
    }

    public function __get($name)
    {
        if (! isset($this->data->$name)) {
            throw new RuntimeException(sprintf('The property [%s] does not exist on %s with %s page data.', $name, static::class, get_class($this->data)));
        }

        return $this->data->$name;
    }

    public function page(?string $page): PageContract
    {
        if (is_string($page)) {
            if (! is_subclass_of($page, PageData::class, true)) {
                throw new Exception(sprintf('The page data class [%s] has to extend %s.', $page, PageData::class));
            }
        }

        $this->page = $page;

        $this->parse();

        return $this;
    }

    public function data(array $data): PageContract
    {
        $this->data = $this->prepareData($data);

        $this->parse();

        return $this;
    }

    public function view(?string $view): PageContract
    {
        $this->view = $view;

        return $this;
    }

    public function render(): View
    {
        if (empty($this->view)) {
            throw new Exception('You have to define a view before the page can render.');
        }

        return $this->viewFactory->make($this->view, $this->toArray());
    }

    public function toHtml(): string
    {
        return $this->render()->render();
    }

    public function toArray(): array
    {
        return is_array($this->data) ? $this->data : $this->data->all();
    }

    public function jsonSerialize(): array
    {
        return is_array($this->data) ? $this->data : $this->data->toArray();
    }

    /** {@inheritdoc} */
    public function toJson($options = 0): string
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    /** {@inheritdoc} */
    public function toResponse($request): Response
    {
        if ($request->wantsJson()) {
            return response()->json($this->jsonSerialize());
        }

        return response($this->render());
    }

    public function toFeedItem(): FeedItem
    {
        if (! ($this->data instanceof PageData)) {
            throw new Exception(sprintf('The page data has to extend %s to allow transformation to %s.', PageData::class, FeedItem::class));
        }

        return $this->data->toFeedItem();
    }

    public function toSitemapItem(): Tag
    {
        if (! ($this->data instanceof PageData)) {
            throw new Exception(sprintf('The page data has to extend %s to allow transformation to %s.', PageData::class, Tag::class));
        }

        return $this->data->toSitemapItem();
    }

    public function getUrl(): string
    {
        if (! ($this->data instanceof Routable)) {
            throw new Exception(sprintf('The page data has to be instance of %s to allow access to the URL.', Routable::class));
        }

        return $this->data->getUrl();
    }

    protected function parse(): void
    {
        if ($this->page === null) {
            return;
        }

        $this->data = forward_static_call(
            [$this->page, 'make'],
            $this->toArray()
        );
    }

    protected function getSheetData(array $sheets): array
    {
        if (empty($sheets)) {
            return [];
        }

        if (! Arr::isAssoc($sheets)) {
            throw new RuntimeException('The [_sheets] data has to be an associative array.');
        }

        $data = [];

        foreach ($sheets as $key => $sheet) {
            [$collection, $path] = explode(':', $sheet);

            if ($path === '*') {
                $data[$key] = Sheets::collection($collection)->all()->map(function (Sheet $sheet) {
                    return $this->prepareData($sheet->toArray());
                })->all();

                continue;
            }

            $data[$key] = $this->prepareData(Sheets::collection($collection)->get($path)->toArray());
        }

        return $data;
    }

    protected function prepareData(array $data): array
    {
        unset($data['_view'], $data['_pageData'], $data['_sheets']);

        return $data;
    }
}
