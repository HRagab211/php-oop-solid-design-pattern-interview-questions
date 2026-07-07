<?php

declare(strict_types=1);

namespace DesignPatterns\Behavioral;

interface DiscountStrategy
{
    public function discountFor(int $subtotalCents): int;
}

final class NoDiscount implements DiscountStrategy
{
    public function discountFor(int $subtotalCents): int
    {
        return 0;
    }
}

final class PercentageDiscount implements DiscountStrategy
{
    public function __construct(private readonly int $percent) {}

    public function discountFor(int $subtotalCents): int
    {
        return (int) round($subtotalCents * $this->percent / 100);
    }
}

final readonly class CartPricer
{
    public function __construct(private DiscountStrategy $discount) {}

    public function total(int $subtotalCents): int
    {
        return $subtotalCents - $this->discount->discountFor($subtotalCents);
    }
}

final class Strategy
{
    public static function run(): array
    {
        return [
            'Regular: ' . (new CartPricer(new NoDiscount()))->total(10000),
            'VIP: ' . (new CartPricer(new PercentageDiscount(15)))->total(10000),
        ];
    }
}
