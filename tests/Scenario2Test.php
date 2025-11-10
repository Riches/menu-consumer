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
final class Scenario2Test extends TestCase
{
    private GreatFood $client;

    protected function setUp(): void
    {
        $httpClient = new MockHttpClient(new GreatFoodMockResponseLocator());
        $tokenProvider = new GreatFoodAuthClient($httpClient, '1337', '4j3g4gj304gj3');
        $this->client = new GreatFood($httpClient, $tokenProvider);
    }

    public function testItUpdatesTheProductNameSuccessfully(): void
    {
        $updated = $this->client->updateProduct(
            7,
            new Product(84, 'Chips'),
        );

        self::assertTrue($updated);
    }
}
