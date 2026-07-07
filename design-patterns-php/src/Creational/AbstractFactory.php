<?php

declare(strict_types=1);

namespace DesignPatterns\Creational;

interface InvoiceRenderer
{
    public function render(string $invoiceNumber): string;
}

interface ReceiptSender
{
    public function send(string $recipient): string;
}

final class PdfInvoiceRenderer implements InvoiceRenderer
{
    public function render(string $invoiceNumber): string
    {
        return "PDF invoice {$invoiceNumber}";
    }
}

final class PdfReceiptSender implements ReceiptSender
{
    public function send(string $recipient): string
    {
        return "PDF receipt emailed to {$recipient}";
    }
}

final class HtmlInvoiceRenderer implements InvoiceRenderer
{
    public function render(string $invoiceNumber): string
    {
        return "HTML invoice {$invoiceNumber}";
    }
}

final class HtmlReceiptSender implements ReceiptSender
{
    public function send(string $recipient): string
    {
        return "HTML receipt page generated for {$recipient}";
    }
}

interface BillingUiFactory
{
    public function invoiceRenderer(): InvoiceRenderer;
    public function receiptSender(): ReceiptSender;
}

final class PdfBillingFactory implements BillingUiFactory
{
    public function invoiceRenderer(): InvoiceRenderer
    {
        return new PdfInvoiceRenderer();
    }

    public function receiptSender(): ReceiptSender
    {
        return new PdfReceiptSender();
    }
}

final class HtmlBillingFactory implements BillingUiFactory
{
    public function invoiceRenderer(): InvoiceRenderer
    {
        return new HtmlInvoiceRenderer();
    }

    public function receiptSender(): ReceiptSender
    {
        return new HtmlReceiptSender();
    }
}

final class AbstractFactory
{
    public static function issue(BillingUiFactory $factory): array
    {
        return [
            $factory->invoiceRenderer()->render('INV-1001'),
            $factory->receiptSender()->send('customer@example.test'),
        ];
    }

    public static function run(): array
    {
        return [
            ...self::issue(new PdfBillingFactory()),
            ...self::issue(new HtmlBillingFactory()),
        ];
    }
}
