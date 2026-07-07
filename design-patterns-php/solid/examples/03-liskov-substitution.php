<?php

declare(strict_types=1);

namespace SolidExamples\LiskovSubstitution;

use RuntimeException;

final readonly class PaymentResult
{
    public function __construct(
        public string $provider,
        public string $transactionId,
    ) {}
}

interface PaymentGateway
{
    public function charge(int $amountCents): PaymentResult;
}

final class StripeGateway implements PaymentGateway
{
    public function charge(int $amountCents): PaymentResult
    {
        if ($amountCents <= 0) {
            throw new RuntimeException('Payment amount must be positive.');
        }

        return new PaymentResult('stripe', 'txn_stripe_1001');
    }
}

final class FakeGateway implements PaymentGateway
{
    public function charge(int $amountCents): PaymentResult
    {
        if ($amountCents <= 0) {
            throw new RuntimeException('Payment amount must be positive.');
        }

        return new PaymentResult('fake', 'txn_fake_1001');
    }
}

final readonly class PaymentService
{
    public function __construct(private PaymentGateway $gateway) {}

    public function pay(int $amountCents): string
    {
        $result = $this->gateway->charge($amountCents);

        return "Payment captured by {$result->provider} with transaction {$result->transactionId}.";
    }
}

$gateways = [
    new StripeGateway(),
    new FakeGateway(),
];

foreach ($gateways as $gateway) {
    echo (new PaymentService($gateway))->pay(4200) . PHP_EOL;
}
