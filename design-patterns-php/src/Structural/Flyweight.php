<?php

declare(strict_types=1);

namespace DesignPatterns\Structural;

final readonly class MoneyFormatter
{
    public function __construct(private string $currency, private string $locale) {}

    public function format(int $cents): string
    {
        return "{$this->locale} {$this->currency} " . number_format($cents / 100, 2);
    }
}

final class MoneyFormatterFactory
{
    private array $formatters = [];

    public function get(string $currency, string $locale): MoneyFormatter
    {
        $key = "{$currency}:{$locale}";
        return $this->formatters[$key] ??= new MoneyFormatter($currency, $locale);
    }
}

final class Flyweight
{
    public static function run(): array
    {
        $factory = new MoneyFormatterFactory();
        $usd = $factory->get('USD', 'en_US');

        return [
            $usd->format(1200),
            $factory->get('USD', 'en_US') === $usd ? 'Formatter reused.' : 'Formatter duplicated.',
        ];
    }
}
