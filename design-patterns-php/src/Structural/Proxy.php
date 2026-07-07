<?php

declare(strict_types=1);

namespace DesignPatterns\Structural;

interface ProductCatalog
{
    public function findPrice(string $sku): int;
}

final class RemoteProductCatalog implements ProductCatalog
{
    public function findPrice(string $sku): int
    {
        return ['SKU-1' => 1200, 'SKU-2' => 3400][$sku] ?? 0;
    }
}

final class CachedProductCatalog implements ProductCatalog
{
    private array $cache = [];

    public function __construct(private readonly ProductCatalog $inner) {}

    public function findPrice(string $sku): int
    {
        return $this->cache[$sku] ??= $this->inner->findPrice($sku);
    }
}

final class Proxy
{
    public static function run(): array
    {
        $catalog = new CachedProductCatalog(new RemoteProductCatalog());
        return [
            'First lookup: ' . $catalog->findPrice('SKU-1'),
            'Second lookup from cache: ' . $catalog->findPrice('SKU-1'),
        ];
    }
}
