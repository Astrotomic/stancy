<?php

namespace Astrotomic\Stancy\Models;

use Exception;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Contracts\View\View;
use Spatie\Sheets\Facades\Sheets;
use Symfony\Component\HttpFoundation\Response;

class Page implements Htmlable, Renderable, Responsable
{
    /** @var string|null */
    protected $view;

    /** @var string|null */
    protected $page;

    /** @var PageData|array */
    protected $data = [];

    /** @var ViewFactory */
    protected $viewFactory;

    public function __construct(ViewFactory $viewFactory, array $data = [], ?string $page = null)
    {
        $this->viewFactory = $viewFactory;
        $this->data($data);
        $this->page($page);
    }

    public static function make(array $data = [], ?string $page = null): self
    {
        return app(static::class, [
            'data' => $data,
            'page' => $page,
        ]);
    }

    public static function makeFromSheet(string $collection, string $name, ?string $page = null): self
    {
        $sheet = Sheets::collection($collection)->get($name);

        if ($sheet === null) {
            throw new Exception(sprintf('No sheet found in collection [%s] with name [%s].', $collection, $name));
        }

        return static::make($sheet->toArray(), $page);
    }

    public function page(?string $page): self
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

    public function data(array $data): self
    {
        $this->data = $data;

        $this->parse();

        return $this;
    }

    public function view(string $view): self
    {
        $this->view = $view;

        return $this;
    }

    public function render(): View
    {
        if (empty($this->view)) {
            throw new Exception('You have to define a view before the page can render.');
        }

        return $this->viewFactory->make($this->view, $this->data);
    }

    public function toHtml(): string
    {
        return $this->render()->render();
    }

    /**
     * @inheritDoc
     */
    public function toResponse($request): Response
    {
        if ($request->wantsJson()) {
            return response()->json($this->data);
        }

        return response($this->render());
    }

    protected function parse(): void
    {
        if ($this->page === null) {
            return;
        }

        $this->data = forward_static_call(
            [$this->page, 'make'],
            is_array($this->data) ? $this->data : $this->data->toArray()
        );
    }
}
