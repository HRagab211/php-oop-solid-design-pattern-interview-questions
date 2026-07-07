<?php

declare(strict_types=1);

namespace SolidExamples\DependencyInversion;

interface OrderRepository
{
    public function markAsPaid(string $orderId): string;
}

interface PaymentGateway
{
    public function charge(string $orderId, int $amountCents): string;
}

final class InMemoryOrderRepository implements OrderRepository
{
    public function markAsPaid(string $orderId): string
    {
        return "Order {$orderId} marked as paid.";
    }
}

final class StripePaymentGateway implements PaymentGateway
{
    public function charge(string $orderId, int $amountCents): string
    {
        return "Stripe charged {$amountCents} cents for order {$orderId}.";
    }
}

final readonly class PayOrderService
{
    public function __construct(
        private OrderRepository $orders,
        private PaymentGateway $payments,
    ) {}

    public function pay(string $orderId, int $amountCents): array
    {
        return [
            $this->payments->charge($orderId, $amountCents),
            $this->orders->markAsPaid($orderId),
        ];
    }
}

$service = new PayOrderService(
    new InMemoryOrderRepository(),
    new StripePaymentGateway(),
);

foreach ($service->pay('ORD-1001', 9900) as $line) {
    echo $line . PHP_EOL;
}
