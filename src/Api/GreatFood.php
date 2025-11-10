<?php

namespace Riches\MenuConsumer\Api;

use Riches\MenuConsumer\Auth\TokenProvider;
use Riches\MenuConsumer\Http\HttpClient;
use Riches\MenuConsumer\Model\GreatFood\Menu;
use Riches\MenuConsumer\Model\GreatFood\Product;

class GreatFood
{
    private HttpClient $httpClient;
    private TokenProvider $tokenProvider;

    public function __construct(HttpClient $httpClient, TokenProvider $tokenProvider)
    {
        $this->httpClient = $httpClient;
        $this->tokenProvider = $tokenProvider;
    }

    /**
     * @return Menu[]
     */
    public function getMenus(): array
    {
        $response = $this->httpClient->get('/menus', $this->authHeaders());

        if ($response['status'] >= 400) {
            throw new \RuntimeException('Failed to fetch menus, status ' . $response['status']);
        }

        $json = $response['json'] ?? null;
        if (!is_array($json) || !isset($json['data']) || !is_array($json['data'])) {
            throw new \RuntimeException('Unexpected menus response format.');
        }

        $menus = [];
        foreach ($json['data'] as $item) {
            $menus[] = Menu::fromArray($item);
        }

        return $menus;
    }

    public function getMenuByName(string $name): ?Menu
    {
        foreach ($this->getMenus() as $menu) {
            if ($menu->getName() === $name) {
                return $menu;
            }
        }

        return null;
    }

    /**
     * @return Product[]
     */
    public function getMenuProducts(int $menuId): array
    {
        $path = '/menu/' . $menuId . '/products';
        $response = $this->httpClient->get($path, $this->authHeaders());

        if ($response['status'] >= 400) {
            throw new \RuntimeException('Failed to fetch products, status ' . $response['status']);
        }

        $json = $response['json'] ?? null;
        if (!is_array($json) || !isset($json['data']) || !is_array($json['data'])) {
            throw new \RuntimeException('Unexpected products response format.');
        }

        $products = [];
        foreach ($json['data'] as $item) {
            $products[] = Product::fromArray($item);
        }

        return $products;
    }

    public function updateProduct(int $menuId, Product $product): bool
    {
        $path = '/menu/' . $menuId . '/product/' . $product->getId();

        $response = $this->httpClient->putJson(
            $path,
            $product->toArray(),
            $this->authHeaders(),
        );

        return $response['status'] >= 200 && $response['status'] < 300;
    }

    private function authHeaders(): array
    {
        $token = $this->tokenProvider->getToken();

        return [
            'Authorization' => 'Bearer ' . $token,
        ];
    }
}
