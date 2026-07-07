<?php

declare(strict_types=1);

namespace DesignPatterns\Architectural;

use DateTimeImmutable;

interface DomainEventContract
{
    public function occurredAt(): DateTimeImmutable;
}

final readonly class OrderWasPaid implements DomainEventContract
{
    public function __construct(public string $orderId, private DateTimeImmutable $time = new DateTimeImmutable()) {}

    public function occurredAt(): DateTimeImmutable
    {
        return $this->time;
    }
}

final class EventRecorder
{
    /** @var DomainEventContract[] */
    private array $events = [];

    public function record(DomainEventContract $event): void
    {
        $this->events[] = $event;
    }

    public function release(): array
    {
        return $this->events;
    }
}

final class DomainEvent
{
    public static function run(): array
    {
        $recorder = new EventRecorder();
        $recorder->record(new OrderWasPaid('ORD-9'));

        return array_map(
            static fn (DomainEventContract $event): string => $event::class . ' at ' . $event->occurredAt()->format('Y-m-d'),
            $recorder->release()
        );
    }
}
