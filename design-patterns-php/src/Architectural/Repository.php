<?php

declare(strict_types=1);

namespace DesignPatterns\Architectural;

final readonly class OrderRecord
{
    public function __construct(public string $id, public string $status, public int $totalCents) {}
}

interface OrderRepository
{
    public function find(string $id): ?OrderRecord;
    public function save(OrderRecord $order): void;
}

final class InMemoryOrderRepository implements OrderRepository
{
    /** @var array<string, OrderRecord> */
    private array $orders = [];

    public function find(string $id): ?OrderRecord
    {
        return $this->orders[$id] ?? null;
    }

    public function save(OrderRecord $order): void
    {
        $this->orders[$order->id] = $order;
    }
}

final class Repository
{
    public static function run(): array
    {
        $repo = new InMemoryOrderRepository();
        $repo->save(new OrderRecord('ORD-1', 'paid', 1200));

        return [$repo->find('ORD-1')?->status ?? 'missing'];
    }
}
