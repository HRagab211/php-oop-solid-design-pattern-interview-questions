<?php

declare(strict_types=1);

namespace DesignPatterns\Structural;

interface MenuNode
{
    public function render(): string;
}

final readonly class MenuItem implements MenuNode
{
    public function __construct(private string $label) {}

    public function render(): string
    {
        return $this->label;
    }
}

final class MenuGroup implements MenuNode
{
    /** @var MenuNode[] */
    private array $children = [];

    public function __construct(private readonly string $label) {}

    public function add(MenuNode $node): void
    {
        $this->children[] = $node;
    }

    public function render(): string
    {
        return $this->label . ' [' . implode(', ', array_map(
            static fn (MenuNode $node): string => $node->render(),
            $this->children
        )) . ']';
    }
}

final class Composite
{
    public static function run(): array
    {
        $admin = new MenuGroup('Admin');
        $admin->add(new MenuItem('Users'));
        $admin->add(new MenuItem('Roles'));

        return [$admin->render()];
    }
}
