<?php

declare(strict_types=1);

namespace DesignPatterns\Behavioral;

abstract class SupportHandler
{
    private ?SupportHandler $next = null;

    public function setNext(SupportHandler $next): SupportHandler
    {
        $this->next = $next;
        return $next;
    }

    public function handle(string $ticket): string
    {
        return $this->canHandle($ticket)
            ? $this->response($ticket)
            : ($this->next?->handle($ticket) ?? 'No handler available.');
    }

    abstract protected function canHandle(string $ticket): bool;
    abstract protected function response(string $ticket): string;
}

final class BillingSupport extends SupportHandler
{
    protected function canHandle(string $ticket): bool
    {
        return str_contains($ticket, 'invoice');
    }

    protected function response(string $ticket): string
    {
        return 'Billing team handled ticket.';
    }
}

final class TechnicalSupport extends SupportHandler
{
    protected function canHandle(string $ticket): bool
    {
        return str_contains($ticket, 'api');
    }

    protected function response(string $ticket): string
    {
        return 'Technical team handled ticket.';
    }
}

final class ChainOfResponsibility
{
    public static function run(): array
    {
        $billing = new BillingSupport();
        $billing->setNext(new TechnicalSupport());

        return [$billing->handle('api authentication failure')];
    }
}
