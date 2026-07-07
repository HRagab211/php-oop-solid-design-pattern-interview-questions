<?php

declare(strict_types=1);

namespace DesignPatterns\Behavioral;

interface OrderObserver
{
    public function orderPaid(string $orderId): string;
}

final class SendReceiptObserver implements OrderObserver
{
    public function orderPaid(string $orderId): string
    {
        return "Receipt sent for {$orderId}";
    }
}

final class UpdateAnalyticsObserver implements OrderObserver
{
    public function orderPaid(string $orderId): string
    {
        return "Analytics updated for {$orderId}";
    }
}

final class OrderSubject
{
    /** @var OrderObserver[] */
    private array $observers = [];

    public function attach(OrderObserver $observer): void
    {
        $this->observers[] = $observer;
    }

    public function markPaid(string $orderId): array
    {
        return array_map(static fn (OrderObserver $observer): string => $observer->orderPaid($orderId), $this->observers);
    }
}

final class Observer
{
    public static function run(): array
    {
        $order = new OrderSubject();
        $order->attach(new SendReceiptObserver());
        $order->attach(new UpdateAnalyticsObserver());

        return $order->markPaid('ORD-1001');
    }
}
