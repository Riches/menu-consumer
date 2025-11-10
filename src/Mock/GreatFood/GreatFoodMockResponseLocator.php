<?php

namespace Riches\MenuConsumer\Mock\GreatFood;

use Riches\MenuConsumer\Http\MockHttpClient;
use Riches\MenuConsumer\Mock\FileSystemMockResponseLocator;

final class GreatFoodMockResponseLocator extends FileSystemMockResponseLocator
{
    public function __construct(string $fixturesRoot = __DIR__ . '/../../responses/GreatFood')
    {
        parent::__construct(
            $fixturesRoot,
            [
                MockHttpClient::METHOD_GET => [
                    '#^/menus$#' => 'menus.json',
                    '#^/menu/\d+/products$#' => 'menu-products.json',
                ],
                MockHttpClient::METHOD_POST_FORM => [
                    '#^/auth_token$#' => 'token.json',
                ],
                MockHttpClient::METHOD_PUT => [
                    '#^/menu/\d+/product/\d+$#' => 'menu-product-update.json',
                ],
            ],
        );
    }
}
