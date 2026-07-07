<?php

declare(strict_types=1);

namespace DesignPatterns\Architectural;

final readonly class CancelOrderAction
{
    public function execute(string $orderId, string $reason): string
    {
        return "Order {$orderId} cancelled because {$reason}.";
    }
}

final class ActionClass
{
    public static function run(): array
    {
        return [(new CancelOrderAction())->execute('ORD-2', 'customer request')];
    }
}
