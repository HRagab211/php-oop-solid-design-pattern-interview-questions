<?php

declare(strict_types=1);

namespace DesignPatterns\Architectural;

use InvalidArgumentException;

final readonly class EmailAddress
{
    public function __construct(public string $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Invalid email address.');
        }
    }

    public function domain(): string
    {
        return substr(strrchr($this->value, '@'), 1);
    }
}

final class ValueObject
{
    public static function run(): array
    {
        $email = new EmailAddress('billing@example.com');
        return ["Domain: {$email->domain()}"];
    }
}
