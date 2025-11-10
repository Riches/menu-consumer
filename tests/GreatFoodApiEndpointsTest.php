<?php

declare(strict_types=1);

namespace Riches\MenuConsumer\Tests;

use PHPUnit\Framework\TestCase;
use Riches\MenuConsumer\Api\GreatFood;
use Riches\MenuConsumer\Auth\GreatFood\GreatFoodAuthClient;
use Riches\MenuConsumer\Http\MockHttpClient;
use Riches\MenuConsumer\Mock\GreatFood\GreatFoodMockResponseLocator;
use Riches\MenuConsumer\Model\GreatFood\Menu;
use Riches\MenuConsumer\Model\GreatFood\Product;

/**
 * @internal
 *
 * @coversNothing
 */
final class GreatFoodApiEndpointsTest extends TestCase
{
    private MockHttpClient $httpClient;
    private GreatFoodAuthClient $tokenProvider;
    private GreatFood $client;

    protected function setUp(): void
    {
        $this->httpClient = new MockHttpClient(new GreatFoodMockResponseLocator());
        $this->tokenProvider = new GreatFoodAuthClient($this->httpClient, '1337', '4j3g4gj304gj3');
        $this->client = new GreatFood($this->httpClient, $this->tokenProvider);
    }

    public function testAuthTokenEndpointReturnsExpectedAccessToken(): void
    {
        $token = $this->tokenProvider->getToken();

        self::assertSame('33w4yh344go3u4h34yh93n4h3un4g34g', $token);
    }

    public function testMenusEndpointReturnsCompleteMenuList(): void
    {
        $menus = $this->client->getMenus();

        self::assertCount(5, $menus);

        $menuPayload = array_map(
            static fn (Menu $menu): array => $menu->toArray(),
            $menus,
        );

        self::assertSame(
            [
                ['id' => 1, 'name' => 'Starters'],
                ['id' => 2, 'name' => 'Mains'],
                ['id' => 3, 'name' => 'Takeaway'],
                ['id' => 4, 'name' => 'Delivery'],
                ['id' => 5, 'name' => 'Desserts'],
            ],
            $menuPayload,
        );
    }

    public function testMenuProductsEndpointReturnsExpectedProducts(): void
    {
        $products = $this->client->getMenuProducts(3);

        self::assertNotEmpty($products);

        $productPayload = array_map(
            static fn (Product $product): array => $product->toArray(),
            $products,
        );

        self::assertSame(
            [
                ['id' => 1, 'name' => 'Large Pizza'],
                ['id' => 2, 'name' => 'Medium Pizza'],
                ['id' => 3, 'name' => 'Burger'],
                ['id' => 4, 'name' => 'Chips'],
                ['id' => 5, 'name' => 'Soup'],
                ['id' => 6, 'name' => 'Salad'],
                ['id' => 84, 'name' => 'Chpis'],
            ],
            $productPayload,
        );
    }
}
