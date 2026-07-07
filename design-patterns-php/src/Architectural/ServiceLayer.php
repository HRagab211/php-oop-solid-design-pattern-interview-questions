<?php

declare(strict_types=1);

namespace DesignPatterns\Architectural;

final readonly class TaxCalculator
{
    public function calculate(int $subtotalCents): int
    {
        return (int) round($subtotalCents * 0.14);
    }
}

final readonly class CheckoutService
{
    public function __construct(private TaxCalculator $taxCalculator) {}

    public function quote(int $subtotalCents): int
    {
        return $subtotalCents + $this->taxCalculator->calculate($subtotalCents);
    }
}

final class ServiceLayer
{
    public static function run(): array
    {
        return ['Total with tax: ' . (new CheckoutService(new TaxCalculator()))->quote(10000)];
    }
}
