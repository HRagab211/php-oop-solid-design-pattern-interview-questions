<?php

declare(strict_types=1);

namespace DesignPatterns\Behavioral;

use IteratorAggregate;
use Traversable;

final readonly class InvoiceLine
{
    public function __construct(public string $description, public int $amountCents) {}
}

final class InvoiceLines implements IteratorAggregate
{
    /** @param InvoiceLine[] $lines */
    public function __construct(private readonly array $lines) {}

    public function getIterator(): Traversable
    {
        yield from $this->lines;
    }
}

final class Iterator
{
    public static function run(): array
    {
        $lines = new InvoiceLines([
            new InvoiceLine('Hosting', 2000),
            new InvoiceLine('Support', 5000),
        ]);

        $output = [];
        foreach ($lines as $line) {
            $output[] = "{$line->description}: {$line->amountCents}";
        }

        return $output;
    }
}
