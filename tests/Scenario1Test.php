<?php

declare(strict_types=1);

namespace Riches\MenuConsumer\Tests;

use PHPUnit\Framework\TestCase;
use Riches\MenuConsumer\Api\GreatFood;
use Riches\MenuConsumer\Auth\GreatFood\GreatFoodAuthClient;
use Riches\MenuConsumer\Http\MockHttpClient;
use Riches\MenuConsumer\Mock\GreatFood\GreatFoodMockResponseLocator;
use Riches\MenuConsumer\Model\GreatFood\Product;

/**
 * @internal
 *
 * @coversNothing
 */
final class Scenario1Test extends TestCase
{
    private GreatFood $client;

    protected function setUp(): void
    {
        $httpClient = new MockHttpClient(new GreatFoodMockResponseLocator());
        $tokenProvider = new GreatFoodAuthClient($httpClient, '1337', '4j3g4gj304gj3');
        $this->client = new GreatFood($httpClient, $tokenProvider);
    }

    public function testItListsTheTakeawayMenuProducts(): void
    {
        $menu = $this->client->getMenuByName('Takeaway');

        self::assertNotNull($menu, 'Expected the Takeaway menu to be available from the API');
        self::assertSame(3, $menu->getId(), 'The Takeaway menu should have id 3 in the mock payload');

        $products = $this->client->getMenuProducts($menu->getId());
        self::assertNotEmpty($products, 'Products should be returned for the Takeaway menu');

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
