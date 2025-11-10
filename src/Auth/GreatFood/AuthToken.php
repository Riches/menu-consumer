<?php

namespace Riches\MenuConsumer\Auth\GreatFood;

final class AuthToken
{
    private string $accessToken;
    private int $expiresIn;
    private string $tokenType;
    private string $scope;

    public function __construct(
        string $accessToken,
        int $expiresIn,
        string $tokenType,
        string $scope,
    ) {
        $this->accessToken = $accessToken;
        $this->expiresIn = $expiresIn;
        $this->tokenType = $tokenType;
        $this->scope = $scope;
    }

    /**
     * @param array{access_token: string, expires_in: int, token_type: string, scope: string} $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['access_token'],
            $data['expires_in'],
            $data['token_type'],
            $data['scope'],
        );
    }

    /**
     * @return array{access_token: string, expires_in: int, token_type: string, scope: string}
     */
    public function toArray(): array
    {
        return [
            'access_token' => $this->accessToken,
            'expires_in' => $this->expiresIn,
            'token_type' => $this->tokenType,
            'scope' => $this->scope,
        ];
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function getTokenType(): string
    {
        return $this->tokenType;
    }

    public function getScope(): string
    {
        return $this->scope;
    }
}
