# Installation

This repository is a plain PHP 8.2+ learning project. It does not require Laravel, a database, Node.js, Docker, or external services.

## Requirements

| Tool | Version | Required |
| --- | --- | --- |
| PHP | 8.2+ | Yes |
| Composer | 2.x | Recommended |
| Database | None | No |
| Node.js | None | No |

## Setup

```bash
git clone <repository-url>
cd design-patterns-php
composer install
```

Composer is recommended for PSR-4 autoloading. The examples also include a small fallback autoloader so they can run before `composer install`.

## Environment

No secrets or real environment values are required.

```bash
cp .env.example .env
```

The `.env` file is optional and ignored by Git.

## Run Examples

```bash
php examples/run-all.php
```

Run one example:

```bash
php examples/behavioral/strategy.php
```

## Quality Checks

```bash
composer validate --no-check-publish
find src examples -name '*.php' -print -exec php -l {} \;
php examples/run-all.php
```
