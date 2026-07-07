<?php

declare(strict_types=1);

namespace DesignPatterns\Architectural;

final readonly class PaidOrdersQuery
{
    public function __construct(private int $minimumTotalCents) {}

    public function apply(array $orders): array
    {
        return array_values(array_filter(
            $orders,
            fn (array $order): bool => $order['status'] === 'paid' && $order['total'] >= $this->minimumTotalCents
        ));
    }
}

final class QueryObject
{
    public static function run(): array
    {
        $orders = [
            ['id' => 'ORD-1', 'status' => 'paid', 'total' => 1200],
            ['id' => 'ORD-2', 'status' => 'pending', 'total' => 5000],
            ['id' => 'ORD-3', 'status' => 'paid', 'total' => 9000],
        ];

        return array_map(
            static fn (array $order): string => $order['id'],
            (new PaidOrdersQuery(2000))->apply($orders)
        );
    }
}
