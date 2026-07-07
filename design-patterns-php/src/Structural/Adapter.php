<?php

declare(strict_types=1);

namespace DesignPatterns\Structural;

interface SmsSender
{
    public function send(string $to, string $message): string;
}

final class LegacySmsClient
{
    public function pushMessage(string $phoneNumber, string $text): string
    {
        return "Legacy SMS to {$phoneNumber}: {$text}";
    }
}

final readonly class LegacySmsAdapter implements SmsSender
{
    public function __construct(private LegacySmsClient $client) {}

    public function send(string $to, string $message): string
    {
        return $this->client->pushMessage($to, $message);
    }
}

final class Adapter
{
    public static function run(): array
    {
        return [(new LegacySmsAdapter(new LegacySmsClient()))->send('+15550100001', 'Your order shipped.')];
    }
}
