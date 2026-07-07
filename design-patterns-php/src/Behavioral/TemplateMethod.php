<?php

declare(strict_types=1);

namespace DesignPatterns\Behavioral;

abstract class CsvImport
{
    final public function import(array $rows): array
    {
        $validRows = array_filter($rows, fn (array $row): bool => $this->isValid($row));
        return array_map(fn (array $row): string => $this->persist($this->normalize($row)), $validRows);
    }

    protected function normalize(array $row): array
    {
        return array_map(static fn (mixed $value): mixed => is_string($value) ? trim($value) : $value, $row);
    }

    abstract protected function isValid(array $row): bool;
    abstract protected function persist(array $row): string;
}

final class ProductCsvImport extends CsvImport
{
    protected function isValid(array $row): bool
    {
        return isset($row['sku'], $row['price']);
    }

    protected function persist(array $row): string
    {
        return "Saved product {$row['sku']}";
    }
}

final class TemplateMethod
{
    public static function run(): array
    {
        return (new ProductCsvImport())->import([
            ['sku' => ' SKU-1 ', 'price' => 1200],
            ['sku' => 'INVALID'],
        ]);
    }
}
