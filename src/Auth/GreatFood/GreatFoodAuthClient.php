<?php

namespace Riches\MenuConsumer\Auth\GreatFood;

use Riches\MenuConsumer\Auth\TokenProvider;
use Riches\MenuConsumer\Http\HttpClient;

final class GreatFoodAuthClient implements TokenProvider
{
    private HttpClient $httpClient;
    private string $clientId;
    private string $clientSecret;
    private string $grantType;
    private ?AuthToken $token = null;

    public function __construct(
        HttpClient $httpClient,
        string $clientId,
        string $clientSecret,
        string $grantType = 'client_credentials',
    ) {
        $this->httpClient = $httpClient;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->grantType = $grantType;
    }

    public function getToken(): string
    {
        if (null !== $this->token) {
            return $this->token->getAccessToken();
        }

        $response = $this->httpClient->postForm('/auth_token', [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type' => $this->grantType,
        ]);

        if ($response['status'] >= 400) {
            throw new \RuntimeException('Auth request failed with status ' . $response['status']);
        }

        if (!isset($response['json']) || !is_array($response['json'])) {
            throw new \RuntimeException('Auth response was not valid JSON.');
        }

        $this->token = AuthToken::fromArray($response['json']);

        return $this->token->getAccessToken();
    }
}
