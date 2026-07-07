<?php

declare(strict_types=1);

namespace DesignPatterns\Behavioral;

interface QueueCommand
{
    public function handle(): string;
}

final readonly class SendInvoiceEmailCommand implements QueueCommand
{
    public function __construct(private string $invoiceNumber) {}

    public function handle(): string
    {
        return "Queued invoice email for {$this->invoiceNumber}";
    }
}

final class CommandBus
{
    public function dispatch(QueueCommand $command): string
    {
        return $command->handle();
    }
}

final class Command
{
    public static function run(): array
    {
        return [(new CommandBus())->dispatch(new SendInvoiceEmailCommand('INV-1001'))];
    }
}
