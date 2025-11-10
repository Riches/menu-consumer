<?php

namespace Riches\MenuConsumer\Mock;

class FileSystemMockResponseLocator implements MockResponseLocator
{
    private string $mockRootDirectory;

    /**
     * @var array<string, array<string, string>>
     */
    private array $routeMap;

    /**
     * @param array<string, array<string, string>> $routeMap
     */
    public function __construct(string $mockRootDirectory, array $routeMap)
    {
        $this->mockRootDirectory = rtrim($mockRootDirectory, DIRECTORY_SEPARATOR);
        $this->routeMap = $routeMap;
    }

    public function locate(string $method, string $path): ?string
    {
        foreach ($this->routeMap[$method] ?? [] as $pattern => $relativePath) {
            if (1 !== preg_match($pattern, $path)) {
                continue;
            }

            $normalizedRelativePath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $relativePath);
            $normalizedRelativePath = ltrim($normalizedRelativePath, DIRECTORY_SEPARATOR);

            return $this->mockRootDirectory . DIRECTORY_SEPARATOR . $normalizedRelativePath;
        }

        return null;
    }
}
