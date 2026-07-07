<?php

declare(strict_types=1);

namespace DesignPatterns\Structural;

interface NotificationChannel
{
    public function send(string $recipient, string $message): string;
}

final class EmailChannel implements NotificationChannel
{
    public function send(string $recipient, string $message): string
    {
        return "Email to {$recipient}: {$message}";
    }
}

final class SlackChannel implements NotificationChannel
{
    public function send(string $recipient, string $message): string
    {
        return "Slack DM to {$recipient}: {$message}";
    }
}

abstract readonly class Notification
{
    public function __construct(protected NotificationChannel $channel) {}

    abstract public function sendTo(string $recipient): string;
}

final readonly class InvoicePaidNotification extends Notification
{
    public function sendTo(string $recipient): string
    {
        return $this->channel->send($recipient, 'Invoice has been paid.');
    }
}

final class Bridge
{
    public static function run(): array
    {
        return [
            (new InvoicePaidNotification(new EmailChannel()))->sendTo('finance@example.com'),
            (new InvoicePaidNotification(new SlackChannel()))->sendTo('@finance'),
        ];
    }
}
