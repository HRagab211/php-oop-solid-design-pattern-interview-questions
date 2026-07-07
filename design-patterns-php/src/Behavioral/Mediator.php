<?php

declare(strict_types=1);

namespace DesignPatterns\Behavioral;

final class CheckoutMediator
{
    public function __construct(
        private readonly InventoryComponent $inventory,
        private readonly PaymentComponent $payment,
        private readonly NotificationComponent $notification,
    ) {}

    public function checkout(string $sku, int $amountCents): array
    {
        return [
            $this->inventory->reserve($sku),
            $this->payment->capture($amountCents),
            $this->notification->send('Order confirmed.'),
        ];
    }
}

final class InventoryComponent
{
    public function reserve(string $sku): string
    {
        return "Inventory reserved for {$sku}";
    }
}

final class PaymentComponent
{
    public function capture(int $amountCents): string
    {
        return "Payment captured for {$amountCents}";
    }
}

final class NotificationComponent
{
    public function send(string $message): string
    {
        return "Notification sent: {$message}";
    }
}

final class Mediator
{
    public static function run(): array
    {
        return (new CheckoutMediator(new InventoryComponent(), new PaymentComponent(), new NotificationComponent()))
            ->checkout('SKU-7', 9900);
    }
}
