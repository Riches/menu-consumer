<?php

namespace Riches\MenuConsumer\Mock;

interface MockResponseLocator
{
    public function locate(string $method, string $path): ?string;
}
