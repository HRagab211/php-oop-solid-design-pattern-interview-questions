<?php

declare(strict_types=1);

namespace DesignPatterns\Architectural;

final readonly class CreateUserData
{
    public function __construct(
        public string $name,
        public string $email,
        public bool $sendWelcomeEmail,
    ) {}

    public static function fromRequest(array $input): self
    {
        return new self(
            name: trim((string) $input['name']),
            email: strtolower(trim((string) $input['email'])),
            sendWelcomeEmail: (bool) ($input['send_welcome_email'] ?? true),
        );
    }
}

final class Dto
{
    public static function run(): array
    {
        $data = CreateUserData::fromRequest([
            'name' => ' Sam ',
            'email' => 'SAM@EXAMPLE.COM',
        ]);

        return ["Create {$data->name} with {$data->email}"];
    }
}
