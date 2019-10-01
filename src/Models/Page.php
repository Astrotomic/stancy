<?php

namespace Astrotomic\Stancy\Models;

use Exception;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Contracts\View\View;
use JsonSerializable;
use Spatie\Feed\Feedable;
use Spatie\Feed\FeedItem;
use Symfony\Component\HttpFoundation\Response;

class Page implements Htmlable, Renderable, Responsable, Arrayable, Jsonable, JsonSerializable, Feedable
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

        $this->view($data['_view'] ?? null);

        $page = $page ?? $data['_pageData'] ?? null;

        unset($data['_view'], $data['_pageData']);

        $this->data($data);
        $this->page($page);
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

    public function view(?string $view): self
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

    public function toArray(): array
    {
        return is_array($this->data) ? $this->data : $this->data->toArray();
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
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
}
