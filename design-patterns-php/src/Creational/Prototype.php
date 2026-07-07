<?php

declare(strict_types=1);

namespace DesignPatterns\Creational;

final class EmailCampaign
{
    public function __construct(
        public string $subject,
        public string $body,
        public array $segments,
    ) {}

    public function __clone(): void
    {
        $this->segments = array_values($this->segments);
    }
}

final class Prototype
{
    public static function run(): array
    {
        $template = new EmailCampaign('Summer sale', 'Use coupon SUMMER.', ['vip']);
        $newCampaign = clone $template;
        $newCampaign->subject = 'Summer sale reminder';
        $newCampaign->segments[] = 'inactive';

        return [
            implode(', ', $template->segments),
            implode(', ', $newCampaign->segments),
        ];
    }
}
