<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Riches\MenuConsumer\Api\GreatFood;
use Riches\MenuConsumer\Auth\GreatFood\GreatFoodAuthClient;
use Riches\MenuConsumer\Http\MockHttpClient;
use Riches\MenuConsumer\Mock\GreatFood\GreatFoodMockResponseLocator;

$httpClient = new MockHttpClient(new GreatFoodMockResponseLocator());
$tokenProvider = new GreatFoodAuthClient(
    $httpClient,
    '1337',
    '4j3g4gj304gj3',
);
$greatFood = new GreatFood($httpClient, $tokenProvider);

$menu = $greatFood->getMenuByName('Takeaway');
if (null === $menu) {
    throw new RuntimeException('Takeaway menu not found.');
}

$products = $greatFood->getMenuProducts($menu->getId());

echo "ID\tName\n";
foreach ($products as $product) {
    printf("%d\t%s\n", $product->getId(), $product->getName());
}
