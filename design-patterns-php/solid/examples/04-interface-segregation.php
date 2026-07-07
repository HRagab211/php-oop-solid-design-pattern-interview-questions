<?php

declare(strict_types=1);

namespace SolidExamples\InterfaceSegregation;

interface ReportGenerator
{
    /** @return array<int, array<string, int|string>> */
    public function generate(): array;
}

interface ReportExporter
{
    /** @param array<int, array<string, int|string>> $rows */
    public function export(array $rows): string;
}

interface ReportNotifier
{
    public function notify(string $recipient, string $filePath): string;
}

final class PaidInvoicesReport implements ReportGenerator
{
    public function generate(): array
    {
        return [
            ['invoice' => 'INV-1001', 'total_cents' => 5000],
            ['invoice' => 'INV-1002', 'total_cents' => 8500],
        ];
    }
}

final class CsvReportExporter implements ReportExporter
{
    public function export(array $rows): string
    {
        return 'paid-invoices.csv with ' . count($rows) . ' rows';
    }
}

final class EmailReportNotifier implements ReportNotifier
{
    public function notify(string $recipient, string $filePath): string
    {
        return "Report {$filePath} sent to {$recipient}.";
    }
}

$report = new PaidInvoicesReport();
$exporter = new CsvReportExporter();
$notifier = new EmailReportNotifier();

$filePath = $exporter->export($report->generate());

echo $filePath . PHP_EOL;
echo $notifier->notify('finance@example.test', $filePath) . PHP_EOL;
