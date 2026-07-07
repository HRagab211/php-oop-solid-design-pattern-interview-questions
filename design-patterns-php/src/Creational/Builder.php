<?php

declare(strict_types=1);

namespace DesignPatterns\Creational;

final readonly class Report
{
    public function __construct(
        public string $title,
        public array $columns,
        public array $filters,
        public string $format,
    ) {}
}

final class ReportBuilder
{
    private string $title = 'Untitled report';
    private array $columns = [];
    private array $filters = [];
    private string $format = 'csv';

    public function titled(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function withColumn(string $column): self
    {
        $this->columns[] = $column;
        return $this;
    }

    public function where(string $field, string $operator, string $value): self
    {
        $this->filters[] = compact('field', 'operator', 'value');
        return $this;
    }

    public function as(string $format): self
    {
        $this->format = $format;
        return $this;
    }

    public function build(): Report
    {
        return new Report($this->title, $this->columns, $this->filters, $this->format);
    }
}

final class Builder
{
    public static function run(): array
    {
        $report = (new ReportBuilder())
            ->titled('Monthly paid invoices')
            ->withColumn('invoice_number')
            ->withColumn('total')
            ->where('status', '=', 'paid')
            ->as('xlsx')
            ->build();

        return ["{$report->title} exports " . implode(', ', $report->columns) . " as {$report->format}."];
    }
}
