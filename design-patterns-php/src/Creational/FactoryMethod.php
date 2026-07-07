<?php

declare(strict_types=1);

namespace DesignPatterns\Creational;

interface PaymentGateway
{
    public function charge(int $amountCents): string;
}

final class StripeGateway implements PaymentGateway
{
    public function charge(int $amountCents): string
    {
        return "Charged {$amountCents} cents using Stripe.";
    }
}

final class PayPalGateway implements PaymentGateway
{
    public function charge(int $amountCents): string
    {
        return "Charged {$amountCents} cents using PayPal.";
    }
}

abstract class Checkout
{
    abstract protected function gateway(): PaymentGateway;

    public function pay(int $amountCents): string
    {
        return $this->gateway()->charge($amountCents);
    }
}

final class StripeCheckout extends Checkout
{
    protected function gateway(): PaymentGateway
    {
        return new StripeGateway();
    }
}

final class PayPalCheckout extends Checkout
{
    protected function gateway(): PaymentGateway
    {
        return new PayPalGateway();
    }
}

final class FactoryMethod
{
    public static function run(): array
    {
        return [
            (new StripeCheckout())->pay(1299),
            (new PayPalCheckout())->pay(2499),
        ];
    }
}
