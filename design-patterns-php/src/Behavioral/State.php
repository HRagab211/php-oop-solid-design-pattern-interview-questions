<?php

declare(strict_types=1);

namespace DesignPatterns\Behavioral;

interface OrderState
{
    public function pay(OrderWorkflow $order): string;
    public function ship(OrderWorkflow $order): string;
}

final class PendingState implements OrderState
{
    public function pay(OrderWorkflow $order): string
    {
        $order->transitionTo(new PaidState());
        return 'Order paid.';
    }

    public function ship(OrderWorkflow $order): string
    {
        return 'Cannot ship before payment.';
    }
}

final class PaidState implements OrderState
{
    public function pay(OrderWorkflow $order): string
    {
        return 'Order already paid.';
    }

    public function ship(OrderWorkflow $order): string
    {
        $order->transitionTo(new ShippedState());
        return 'Order shipped.';
    }
}

final class ShippedState implements OrderState
{
    public function pay(OrderWorkflow $order): string
    {
        return 'Cannot pay shipped order.';
    }

    public function ship(OrderWorkflow $order): string
    {
        return 'Order already shipped.';
    }
}

final class OrderWorkflow
{
    public function __construct(private OrderState $state = new PendingState()) {}

    public function transitionTo(OrderState $state): void
    {
        $this->state = $state;
    }

    public function pay(): string
    {
        return $this->state->pay($this);
    }

    public function ship(): string
    {
        return $this->state->ship($this);
    }
}

final class State
{
    public static function run(): array
    {
        $order = new OrderWorkflow();
        return [$order->ship(), $order->pay(), $order->ship()];
    }
}
