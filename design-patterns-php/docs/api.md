# API Documentation

This repository does not expose an HTTP API.

The public interface is the set of CLI examples under `examples/`.

## CLI Usage

Run all examples:

```bash
php examples/run-all.php
```

Run a specific example:

```bash
php examples/creational/factory-method.php
php examples/structural/adapter.php
php examples/behavioral/strategy.php
php examples/architectural/value-object.php
```

## Source Namespace

All source classes live under:

```text
DesignPatterns\
```

Composer PSR-4 autoloading maps this namespace to `src/`.
