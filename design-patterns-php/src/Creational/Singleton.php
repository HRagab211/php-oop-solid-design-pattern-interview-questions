<?php

declare(strict_types=1);

namespace DesignPatterns\Creational;

final class RuntimeConfig
{
    private static ?self $instance = null;
    private array $values = [];

    private function __construct() {}

    public static function instance(): self
    {
        return self::$instance ??= new self();
    }

    public function set(string $key, string $value): void
    {
        $this->values[$key] = $value;
    }

    public function get(string $key): ?string
    {
        return $this->values[$key] ?? null;
    }
}

final class Singleton
{
    public static function run(): array
    {
        RuntimeConfig::instance()->set('currency', 'USD');

        return [
            RuntimeConfig::instance()->get('currency') === 'USD'
                ? 'Shared runtime config is available.'
                : 'Config missing.',
            'In Laravel, prefer the service container over manual singletons.',
        ];
    }
}
