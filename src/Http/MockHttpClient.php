<?php

namespace Riches\MenuConsumer\Http;

use Riches\MenuConsumer\Mock\MockResponseLocator;

class MockHttpClient implements HttpClient
{
    public const METHOD_GET = 'GET';
    public const METHOD_POST_FORM = 'POST_FORM';
    public const METHOD_PUT = 'PUT';

    private MockResponseLocator $responseLocator;

    public function __construct(MockResponseLocator $responseLocator)
    {
        $this->responseLocator = $responseLocator;
    }

    public function get(string $path, array $headers = []): array
    {
        return $this->loadMockResponse(self::METHOD_GET, $path);
    }

    public function postForm(string $path, array $formData, array $headers = []): array
    {
        return $this->loadMockResponse(self::METHOD_POST_FORM, $path);
    }

    public function putJson(string $path, array $payload, array $headers = []): array
    {
        return $this->loadMockResponse(self::METHOD_PUT, $path);
    }

    private function loadMockResponse(string $method, string $path): array
    {
        $file = $this->responseLocator->locate($method, $path);

        if (null === $file) {
            throw new \RuntimeException(sprintf('No mock defined for %s %s', $method, $path));
        }

        return $this->decodeFile($file);
    }

    private function decodeFile(string $filePath): array
    {
        if (!is_file($filePath)) {
            throw new \RuntimeException(sprintf('Mock file not found: %s', $filePath));
        }

        $contents = file_get_contents($filePath);
        if (false === $contents) {
            throw new \RuntimeException(sprintf('Unable to read mock file: %s', $filePath));
        }

        $data = json_decode($contents, true);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new \RuntimeException(sprintf(
                'Invalid JSON in mock file %s: %s',
                $filePath,
                json_last_error_msg(),
            ));
        }

        return [
            'status' => 200,
            'json' => $data,
        ];
    }
}
