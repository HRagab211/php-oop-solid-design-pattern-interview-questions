# Design Patterns in PHP and Laravel

A practical PHP 8.2+ reference project that demonstrates popular design patterns and Laravel-oriented architecture patterns with runnable CLI examples.

## Overview

This repository is designed for PHP/Laravel developers who want to study design patterns through realistic backend examples instead of abstract toy examples. It includes implementations for creational, structural, behavioral, and Laravel-style architectural patterns, plus interview-focused documentation.

The project is intentionally framework-free. It does not require a Laravel installation, database, frontend toolchain, Docker, or external services. Laravel context is explained in the documentation while the examples remain small, portable, and easy to run from the command line.

## Features

- 28 implemented design and architecture patterns.
- One runnable CLI example for every pattern.
- Practical backend domains: payments, invoices, reports, notifications, orders, caching, DTOs, value objects, and query objects.
- Modern PHP 8.2+ style with strict types, typed properties, interfaces, readonly objects/properties, and clean namespaces.
- PSR-4 autoloading through Composer.
- Fallback autoloader for running examples before Composer is installed.
- Interview-focused documentation for PHP, Laravel, SOLID, SQL, and design-pattern questions.
- Dedicated [SOLID principles guide](solid/README.md) with runnable PHP examples.
- GitHub Actions workflow for Composer validation, PHP syntax checks, and example smoke runs.

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | PHP 8.2+ |
| Framework | None required |
| Package manager | Composer |
| Autoloading | PSR-4 |
| Database | Not required |
| Frontend | Not included |
| Testing | PHP lint checks and CLI smoke examples |
| CI | GitHub Actions |
| Tools | Composer |

## Requirements

- PHP 8.2 or higher
- Composer 2.x recommended

No Node.js, NPM, database, Redis, queue worker, or Docker setup is required.

## Installation

```bash
git clone <repository-url>
cd design-patterns-php
composer install
```

Composer is recommended because the project defines PSR-4 autoloading. The examples also include a fallback autoloader, so they can run even before `composer install`.

## Environment Configuration

This project does not require real environment variables or secrets.

Optional local setup:

```bash
cp .env.example .env
```

Safe example values:

```env
APP_ENV=local
APP_DEBUG=true
```

Do not commit `.env` files. The repository includes `.env.example` only as a safe template for future configurable examples.

## Database Setup

No database setup is required. All examples use in-memory data and run from the command line.

There are no migrations, seeders, factories, or database configuration files because this is not a Laravel application.

## Running the Application

Run all pattern examples:

```bash
php examples/run-all.php
```

Run one example:

```bash
php examples/behavioral/strategy.php
php examples/architectural/value-object.php
php examples/creational/factory-method.php
```

## Running Tests

Automated PHPUnit/Pest tests are not currently included. The repository uses lightweight quality checks:

```bash
composer test
```

This command runs:

- PHP syntax linting for `src/` and `examples/`.
- A smoke run of all CLI examples.

Manual equivalent:

```bash
composer validate --no-check-publish
find src examples -name '*.php' -print -exec php -l {} \;
php examples/run-all.php
```

## Useful Commands

| Command | Purpose |
|---|---|
| `composer install` | Install Composer dependencies and generate autoload files |
| `composer dump-autoload` | Regenerate Composer autoload files |
| `composer validate --no-check-publish` | Validate Composer metadata |
| `composer test` | Run lint checks and all smoke examples |
| `composer run:all` | Run every pattern example |
| `composer solid` | Run all SOLID principle examples |
| `php examples/run-all.php` | Run every pattern example directly |

## Project Structure

```text
design-patterns-php/
├── .github/
│   └── workflows/
│       └── tests.yml
├── docs/
│   ├── api.md
│   ├── architecture.md
│   ├── development-notes.md
│   ├── features.md
│   ├── installation.md
│   ├── interview-notes.md
│   ├── repository-review.md
│   └── top-50-php-laravel-interview-questions.md
├── examples/
│   ├── architectural/
│   ├── behavioral/
│   ├── creational/
│   ├── structural/
│   ├── bootstrap.php
│   └── run-all.php
├── src/
│   ├── Architectural/
│   ├── Behavioral/
│   ├── Creational/
│   └── Structural/
├── solid/
│   ├── examples/
│   └── README.md
├── .env.example
├── .gitignore
├── composer.json
├── LICENSE
└── README.md
```

Folder responsibilities:

| Path | Purpose |
|---|---|
| `src/Creational` | Factory Method, Abstract Factory, Builder, Singleton, Prototype |
| `src/Structural` | Adapter, Decorator, Facade, Proxy, Composite, Bridge, Flyweight |
| `src/Behavioral` | Strategy, Observer, Command, Chain of Responsibility, Template Method, State, Iterator, Mediator, Specification |
| `src/Architectural` | Repository, Service Layer, Action Class, DTO, Value Object, Domain Event, Query Object |
| `examples/` | Runnable CLI examples for each pattern |
| `solid/` | Detailed SOLID principles guide with runnable PHP examples |
| `docs/` | Installation, architecture, features, interview notes, and repository review documentation |
| `.github/workflows` | CI workflow for validation and smoke checks |

## Architecture Overview

The codebase is a small educational PHP project organized by pattern category.

Key architecture choices:

- Each pattern lives in its matching category under `src/`.
- Each runnable example lives under `examples/` using the same category naming.
- Composer maps the `DesignPatterns\` namespace to `src/`.
- The CLI examples load `examples/bootstrap.php`, which uses Composer autoloading when available and falls back to a small local autoloader.
- The examples avoid external services so every pattern can run consistently in local development and CI.

Pattern categories included:

| Category | Patterns |
|---|---|
| Creational | Factory Method, Abstract Factory, Builder, Singleton, Prototype |
| Structural | Adapter, Decorator, Facade, Proxy, Composite, Bridge, Flyweight |
| Behavioral | Strategy, Observer, Command, Chain of Responsibility, Template Method, State, Iterator, Mediator, Specification |
| Architectural | Repository, Service Layer, Action Class, DTO, Value Object, Domain Event, Query Object |

## API Documentation

This project does not expose an HTTP API.

The public interface is the CLI example set:

```bash
php examples/run-all.php
```

More details are available in [docs/api.md](docs/api.md).

## Documentation

| Document | Description |
|---|---|
| [Installation](docs/installation.md) | Setup, environment, and quality commands |
| [Architecture](docs/architecture.md) | Project structure and design decisions |
| [Features](docs/features.md) | Implemented pattern categories and learning material |
| [API Notes](docs/api.md) | CLI usage and namespace reference |
| [Development Notes](docs/development-notes.md) | Coding workflow, hygiene, and security notes |
| [Repository Review](docs/repository-review.md) | GitHub readiness checklist and review notes |
| [Interview Notes](docs/interview-notes.md) | Pattern summaries and Laravel comparisons |
| [Top 50 Questions](docs/top-50-php-laravel-interview-questions.md) | PHP/Laravel interview questions with answers |

## Screenshots

Screenshots will be added soon.

This is currently a CLI/backend reference project, so the most useful output is the terminal result from:

```bash
php examples/run-all.php
```

## Development Notes

- Keep examples small, focused, and runnable.
- Use `declare(strict_types=1)` in PHP files.
- Prefer clear interfaces and explicit names.
- Do not add framework dependencies unless they are necessary for the learning goal.
- Do not commit real credentials, `.env` files, generated logs, `vendor/`, or local editor files.
- When adding a new pattern, add source code, a matching CLI example, documentation, and an entry in `examples/run-all.php`.
- Run `composer test` before pushing.

Known limitations:

- No PHPUnit/Pest suite is included yet.
- Pattern implementations are intentionally compact for learning and interview preparation.
- This is not a deployable Laravel application.

## Security Notes

- Keep `.env` private.
- Do not commit credentials, API keys, tokens, private URLs, real emails, phone numbers, or customer data.
- Use environment variables for any future configurable integrations.
- Keep `APP_DEBUG=false` for real production applications.
- Review third-party dependencies before adding them.

## Deployment Notes

This project does not need application deployment, a web server, queue workers, scheduled tasks, or frontend builds.

For GitHub/CI usage:

```bash
composer install
composer test
```

If the project is later converted into a Laravel application, deployment documentation should be updated with real commands for environment configuration, migrations, caches, queues, scheduler, and frontend builds.

## Contributing

Contributions should keep the repository focused on practical PHP/Laravel learning.

Suggested workflow:

1. Create a branch for the change.
2. Add or update source examples under `src/`.
3. Add or update runnable examples under `examples/`.
4. Update relevant documentation.
5. Run `composer test`.
6. Open a pull request with a clear explanation.

## License

This project is open-sourced under the [MIT License](LICENSE).
