<?php

declare(strict_types=1);

namespace DesignPatterns\Structural;

interface Logger
{
    public function log(string $message): string;
}

final class FileLogger implements Logger
{
    public function log(string $message): string
    {
        return "file: {$message}";
    }
}

final readonly class RequestIdLogger implements Logger
{
    public function __construct(private Logger $inner, private string $requestId) {}

    public function log(string $message): string
    {
        return $this->inner->log("[{$this->requestId}] {$message}");
    }
}

final readonly class JsonLogger implements Logger
{
    public function __construct(private Logger $inner) {}

    public function log(string $message): string
    {
        return $this->inner->log(json_encode(['message' => $message], JSON_THROW_ON_ERROR));
    }
}

final class Decorator
{
    public static function run(): array
    {
        $logger = new JsonLogger(new RequestIdLogger(new FileLogger(), 'req-123'));
        return [$logger->log('Payment captured')];
    }
}
