<?php

declare(strict_types=1);

namespace SolidExamples\SingleResponsibility;

final readonly class Invoice
{
    /** @param array<int, int> $lineAmountsCents */
    public function __construct(
        public string $number,
        public array $lineAmountsCents,
        public string $customerEmail,
    ) {}
}

final class InvoiceTotalCalculator
{
    public function total(Invoice $invoice): int
    {
        return array_sum($invoice->lineAmountsCents);
    }
}

interface PaymentGateway
{
    public function charge(int $amountCents): string;
}

final class FakePaymentGateway implements PaymentGateway
{
    public function charge(int $amountCents): string
    {
        return "Charged {$amountCents} cents.";
    }
}

final class ReceiptSender
{
    public function send(Invoice $invoice): string
    {
        return "Receipt sent to {$invoice->customerEmail}.";
    }
}

final readonly class PayInvoiceAction
{
    public function __construct(
        private InvoiceTotalCalculator $calculator,
        private PaymentGateway $gateway,
        private ReceiptSender $receiptSender,
    ) {}

    public function execute(Invoice $invoice): array
    {
        $total = $this->calculator->total($invoice);

        return [
            "Invoice {$invoice->number} total is {$total} cents.",
            $this->gateway->charge($total),
            $this->receiptSender->send($invoice),
        ];
    }
}

$invoice = new Invoice('INV-1001', [2500, 1500, 1000], 'customer@example.test');
$action = new PayInvoiceAction(
    new InvoiceTotalCalculator(),
    new FakePaymentGateway(),
    new ReceiptSender(),
);

foreach ($action->execute($invoice) as $line) {
    echo $line . PHP_EOL;
}
