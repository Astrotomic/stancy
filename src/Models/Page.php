<?php

namespace Astroromic\Stancy\Models;

use Exception;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Contracts\View\View;
use Spatie\Sheets\Facades\Sheets;

class Page implements Htmlable
{
    /** @var string */
    protected $view;

    /** @var string|null */
    protected $page;

    /** @var PageData|array */
    protected $data;

    /** @var ViewFactory */
    protected $viewFactory;

    public function __construct(ViewFactory $viewFactory, array $data, ?string $page = null)
    {
        $this->viewFactory = $viewFactory;
        $this->page($page);
        $this->data($data);
    }

    public static function make(string $collection, string $name, ?string $page = null): self
    {
        $sheet = Sheets::collection($collection)->get($name);

        return app(static::class, [
            'data' => $sheet->toArray(),
            'page' => $page,
        ]);
    }

    public function page(?string $page): self
    {
        if(is_string($page)) {
            if(!($page instanceof PageData)) {
                throw new Exception(sprintf('The page data class [%s] has to extend %s.', $page, PageData::class));
            }
        }

        $this->page = $page;

        $this->parse();

        return $this;
    }

    public function data(array $data): self
    {
        $this->data = $data;

        $this->parse();

        return $this;
    }

    public function toHtml(): View
    {
        return $this->viewFactory->make($this->view, $this->data);
    }

    protected function parse(): void
    {
        if($this->page === null) {
            return;
        }

        if(is_array($this->data) && empty($this->data)) {
            return;
        }

        $this->data = forward_static_call(
            [$this->page, 'make'],
            is_array($this->data) ? $this->data : $this->data->toArray()
        );
    }
}
