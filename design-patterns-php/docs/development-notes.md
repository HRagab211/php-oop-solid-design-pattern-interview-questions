# Development Notes

## Repository Hygiene

- Do not commit `.env`, local logs, generated coverage, `vendor/`, or editor files.
- Keep examples runnable from the command line.
- Use safe placeholder data only. Avoid real emails, phone numbers, tokens, or customer data.
- Keep pattern examples focused. Do not mix unrelated patterns in one example.

## Adding a New Pattern

1. Add the implementation under the appropriate `src/` category.
2. Add a matching runnable file under `examples/`.
3. Register the example in `examples/run-all.php`.
4. Document it in `README.md` and relevant docs.
5. Run the quality checks.

## Quality Commands

```bash
composer validate --no-check-publish
find src examples -name '*.php' -print -exec php -l {} \;
php examples/run-all.php
```

## Security Notes

This project does not require real credentials. If future examples need provider configuration, use environment variables and document safe placeholders in `.env.example`.
