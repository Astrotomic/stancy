<?php

namespace Astrotomic\Stancy\Contracts;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Contracts\View\View;
use JsonSerializable;
use Spatie\Feed\Feedable;
use Spatie\Feed\FeedItem;
use Symfony\Component\HttpFoundation\Response;

interface Page extends Htmlable, Renderable, Responsable, Arrayable, Jsonable, JsonSerializable, Feedable, Sitemapable, Routable
{
    public function page(?string $page): self;

    public function data(array $data): self;

    public function view(?string $view): self;

    public function render(): View;

    public function toHtml(): string;

    public function toArray(): array;

    public function jsonSerialize(): array;

    public function toJson($options = 0): string;

    public function toResponse($request): Response;

    public function toFeedItem(): FeedItem;
}
