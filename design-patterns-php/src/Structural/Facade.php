<?php

declare(strict_types=1);

namespace DesignPatterns\Structural;

final class InventoryService
{
    public function reserve(string $sku): string
    {
        return "Reserved {$sku}";
    }
}

final class PaymentService
{
    public function capture(int $amountCents): string
    {
        return "Captured {$amountCents} cents";
    }
}

final class ShippingService
{
    public function createLabel(string $sku): string
    {
        return "Shipping label for {$sku}";
    }
}

final readonly class CheckoutFacade
{
    public function __construct(
        private InventoryService $inventory,
        private PaymentService $payment,
        private ShippingService $shipping,
    ) {}

    public function placeOrder(string $sku, int $amountCents): array
    {
        return [
            $this->inventory->reserve($sku),
            $this->payment->capture($amountCents),
            $this->shipping->createLabel($sku),
        ];
    }
}

final class Facade
{
    public static function run(): array
    {
        return (new CheckoutFacade(new InventoryService(), new PaymentService(), new ShippingService()))
            ->placeOrder('SKU-1', 5500);
    }
}
