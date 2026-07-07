# Architecture

This project is intentionally small and framework-free. It is designed to teach design patterns with runnable PHP code while still reflecting real Laravel/backend use cases.

## Structure

```text
src/
├── Creational/
├── Structural/
├── Behavioral/
└── Architectural/

examples/
├── creational/
├── structural/
├── behavioral/
└── architectural/
```

## Design Decisions

- Each pattern has a dedicated source file to keep navigation simple for learners.
- Each pattern has a matching CLI example under `examples/`.
- Examples use backend domains such as payments, invoices, reports, notifications, orders, caching, and query objects.
- Code uses `declare(strict_types=1)`, typed properties, interfaces, readonly classes/properties where useful, and final classes where extension is not expected.
- No framework dependency is required. Laravel context is explained in docs instead of coupling examples to Laravel internals.

## Autoloading

Composer maps:

```json
{
  "DesignPatterns\\": "src/"
}
```

The example bootstrap loads Composer's autoloader when available and falls back to a small PSR-4-compatible loader for convenience.

## Testing Approach

There is no PHPUnit suite because the repository is a compact educational reference. The current quality gate is:

- Composer metadata validation.
- PHP syntax linting.
- CLI smoke run for every example.

This keeps the repository lightweight while still proving that all examples execute.
