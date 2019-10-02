<?php

namespace Astrotomic\Stancy\Contracts;

use Spatie\Feed\FeedItem;
use Illuminate\Contracts\View\View;
use Symfony\Component\HttpFoundation\Response;

interface Page
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