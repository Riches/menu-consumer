<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Riches\MenuConsumer\Api\GreatFood;
use Riches\MenuConsumer\Auth\GreatFood\GreatFoodAuthClient;
use Riches\MenuConsumer\Http\MockHttpClient;
use Riches\MenuConsumer\Mock\GreatFood\GreatFoodMockResponseLocator;
use Riches\MenuConsumer\Model\GreatFood\Product;

$httpClient = new MockHttpClient(new GreatFoodMockResponseLocator());
$tokenProvider = new GreatFoodAuthClient(
    $httpClient,
    '1337',
    '4j3g4gj304gj3',
);
$greatFood = new GreatFood($httpClient, $tokenProvider);

$menuId = 7;
$productId = 84;
$newName = 'Chips';

$updated = $greatFood->updateProduct(
    $menuId,
    new Product($productId, $newName),
);

if ($updated) {
    echo sprintf("Product %s in menu %s updated to '%s'.\n", $menuId, $productId, $newName);
} else {
    throw new RuntimeException('Failed to update product 84 in menu 7.');
}
