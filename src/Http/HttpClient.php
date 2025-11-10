<?php

namespace Riches\MenuConsumer\Http;

interface HttpClient
{
    public function get(string $path, array $headers = []): array;

    public function postForm(string $path, array $formData, array $headers = []): array;

    public function putJson(string $path, array $payload, array $headers = []): array;
}
