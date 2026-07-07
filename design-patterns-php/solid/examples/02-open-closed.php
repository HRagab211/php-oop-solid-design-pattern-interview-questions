<?php

declare(strict_types=1);

namespace SolidExamples\OpenClosed;

interface PaymentMethod
{
    public function pay(int $amountCents): string;
}

final class CardPayment implements PaymentMethod
{
    public function pay(int $amountCents): string
    {
        return "Paid {$amountCents} cents by card.";
    }
}

final class WalletPayment implements PaymentMethod
{
    public function pay(int $amountCents): string
    {
        return "Paid {$amountCents} cents by wallet.";
    }
}

final class BankTransferPayment implements PaymentMethod
{
    public function pay(int $amountCents): string
    {
        return "Created bank transfer request for {$amountCents} cents.";
    }
}

final readonly class CheckoutService
{
    public function __construct(private PaymentMethod $paymentMethod) {}

    public function checkout(int $amountCents): string
    {
        return $this->paymentMethod->pay($amountCents);
    }
}

$payments = [
    new CardPayment(),
    new WalletPayment(),
    new BankTransferPayment(),
];

foreach ($payments as $payment) {
    echo (new CheckoutService($payment))->checkout(7500) . PHP_EOL;
}
