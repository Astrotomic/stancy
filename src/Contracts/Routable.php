<?php

namespace Astrotomic\Stancy\Contracts;

interface Routable
{
    public function getUrl(): string;
}
