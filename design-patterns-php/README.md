# Design Patterns in PHP and Laravel

A practical, runnable PHP 8.2+ reference project for learning design patterns, Laravel-oriented architecture patterns, and backend interview concepts.

This repository is built for GitHub review, portfolio presentation, and technical evaluation. It avoids framework dependencies while using realistic backend examples such as payments, invoices, reports, notifications, orders, caching, DTOs, value objects, and query objects.

## Main Features

- 28 implemented design and architecture patterns.
- One runnable CLI example for every pattern.
- Modern PHP 8.2+ syntax with strict types, interfaces, readonly objects, enums where useful, and clean namespaces.
- Laravel context for each pattern without requiring a Laravel installation.
- Interview-focused documentation and Top 50 PHP/Laravel questions with answers.
- GitHub Actions workflow for Composer validation, syntax linting, and smoke examples.

## Tech Stack

| Area | Tooling |
| --- | --- |
| Language | PHP 8.2+ |
| Package manager | Composer |
| Autoloading | PSR-4 |
| Framework | None required |
| Database | None required |
| Frontend | None |
| CI | GitHub Actions |

## Requirements

- PHP 8.2 or higher
- Composer 2.x recommended

The examples also include a fallback autoloader, so they can run even before Composer dependencies are installed.

## Installation

```bash
git clone <repository-url>
cd design-patterns-php
composer install
```

## Environment Setup

No real environment variables, credentials, API keys, or database settings are required.

```bash
cp .env.example .env
```

The `.env` file is optional and ignored by Git.

## Database Setup

No database is required. All examples are CLI-only and use in-memory sample data.

## Run Locally

Run every example:

```bash
php examples/run-all.php
```

Run a single example:

```bash
php examples/behavioral/strategy.php
```

## Tests and Quality Checks

This project uses lightweight smoke checks instead of a full PHPUnit suite.

```bash
composer validate --no-check-publish
composer test
```

Equivalent manual commands:

```bash
find src examples -name '*.php' -print -exec php -l {} \;
php examples/run-all.php
```

## Useful Commands

| Command | Purpose |
| --- | --- |
| `composer install` | Install Composer dependencies and generate autoload files |
| `composer dump-autoload` | Regenerate Composer autoload files |
| `composer validate --no-check-publish` | Validate Composer metadata |
| `composer test` | Run syntax linting and smoke examples |
| `php examples/run-all.php` | Run every pattern example |

## Project Structure

```text
design-patterns-php/
├── .github/workflows/tests.yml
├── .env.example
├── .gitignore
├── LICENSE
├── composer.json
├── README.md
├── src/
│   ├── Creational/
│   ├── Structural/
│   ├── Behavioral/
│   └── Architectural/
├── examples/
│   ├── creational/
│   ├── structural/
│   ├── behavioral/
│   └── architectural/
└── docs/
    ├── api.md
    ├── architecture.md
    ├── development-notes.md
    ├── features.md
    ├── installation.md
    ├── interview-notes.md
    └── top-50-php-laravel-interview-questions.md
```

## Documentation

| Document | Description |
| --- | --- |
| [Installation](docs/installation.md) | Setup, environment, and quality commands |
| [Architecture](docs/architecture.md) | Project structure and design decisions |
| [Features](docs/features.md) | Implemented categories and learning material |
| [API Notes](docs/api.md) | CLI usage and namespace reference |
| [Development Notes](docs/development-notes.md) | Contribution workflow, hygiene, and security notes |
| [Repository Review](docs/repository-review.md) | GitHub readiness checklist and review notes |
| [Interview Notes](docs/interview-notes.md) | Pattern summaries and Laravel comparisons |
| [Top 50 Questions](docs/top-50-php-laravel-interview-questions.md) | PHP/Laravel interview questions with answers |

## API Documentation

This repository does not expose an HTTP API. The public interface is the CLI examples under `examples/`.

## Screenshots

No screenshots are included because this is a CLI/backend learning project. If a visual site or generated docs page is added later, screenshots can be placed here.

## Security

- No real credentials are required.
- `.env` files are ignored.
- `.env.example` contains safe placeholders only.
- Sample emails and phone numbers use reserved/example-style values and are not customer data.

## Development Notes

Keep examples small, focused, and runnable. When adding a new pattern, add the source implementation, matching CLI example, docs entry, and smoke-run registration.

## License

This project is open-sourced under the [MIT License](LICENSE).

## Quick Index

Extra interview guide: [Top 50 PHP/Laravel Interview Questions With Answers](docs/top-50-php-laravel-interview-questions.md).

| Pattern | Category | Real Use Case | Run |
| --- | --- | --- | --- |
| Factory Method | Creational | Select payment gateway checkout | `php examples/creational/factory-method.php` |
| Abstract Factory | Creational | Create related billing UI services | `php examples/creational/abstract-factory.php` |
| Builder | Creational | Build report export options | `php examples/creational/builder.php` |
| Singleton | Creational | Shared runtime config, with warning | `php examples/creational/singleton.php` |
| Prototype | Creational | Clone email campaign templates | `php examples/creational/prototype.php` |
| Adapter | Structural | Wrap legacy SMS client | `php examples/structural/adapter.php` |
| Decorator | Structural | Add request id and JSON logging | `php examples/structural/decorator.php` |
| Facade | Structural | Simplify checkout workflow | `php examples/structural/facade.php` |
| Proxy | Structural | Cache remote catalog access | `php examples/structural/proxy.php` |
| Composite | Structural | Nested permission/menu tree | `php examples/structural/composite.php` |
| Bridge | Structural | Notification type independent from channel | `php examples/structural/bridge.php` |
| Flyweight | Structural | Reuse immutable formatters | `php examples/structural/flyweight.php` |
| Strategy | Behavioral | Discount calculation rules | `php examples/behavioral/strategy.php` |
| Observer | Behavioral | React to order payment | `php examples/behavioral/observer.php` |
| Command | Behavioral | Queueable invoice email command | `php examples/behavioral/command.php` |
| Chain of Responsibility | Behavioral | Support ticket routing | `php examples/behavioral/chain-of-responsibility.php` |
| Template Method | Behavioral | CSV import pipeline | `php examples/behavioral/template-method.php` |
| State | Behavioral | Order workflow transitions | `php examples/behavioral/state.php` |
| Iterator | Behavioral | Invoice line collection | `php examples/behavioral/iterator.php` |
| Mediator | Behavioral | Checkout components coordination | `php examples/behavioral/mediator.php` |
| Specification | Behavioral | Composable eligibility rules | `php examples/behavioral/specification.php` |
| Repository | Architectural | Order persistence boundary | `php examples/architectural/repository.php` |
| Service Layer | Architectural | Checkout business service | `php examples/architectural/service-layer.php` |
| Action Class | Architectural | Single-purpose order cancellation | `php examples/architectural/action-class.php` |
| DTO | Architectural | Request data transfer object | `php examples/architectural/dto.php` |
| Value Object | Architectural | Validated email address | `php examples/architectural/value-object.php` |
| Domain Event | Architectural | Order paid domain event | `php examples/architectural/domain-event.php` |
| Query Object | Architectural | Reusable paid orders query | `php examples/architectural/query-object.php` |

## Creational Patterns

### Factory Method Pattern

**Problem:** Code needs to create payment gateways, but controllers should not know every concrete gateway class.

**Solution:** Put object creation behind a method implemented by subclasses.

**When to use:** A workflow is stable, but the concrete dependency changes by context.

**When not to use:** A simple constructor or dependency injection binding is enough.

**Laravel use case:** Payment gateway selection, notification drivers, custom object factories. Laravel model factories are related, but they solve test/data creation more than runtime polymorphism.

**Files:** `src/Creational/FactoryMethod.php`, `examples/creational/factory-method.php`

**Run:** `php examples/creational/factory-method.php`

**Interview question:** How would you support multiple payment gateways without large conditionals?

**Strong answer:** Define a gateway interface and let each checkout type decide which gateway it creates. In Laravel, I would often bind the interface in the container or use a factory service when the implementation depends on runtime input.

**Common mistakes:** Hiding simple `new` calls behind factories too early; mixing factory logic with payment business rules.

### Abstract Factory Pattern

**Problem:** A feature needs families of related objects, such as matching invoice renderers and receipt senders.

**Solution:** Create a factory interface that returns each product in the family.

**When to use:** Objects must be compatible and selected together.

**When not to use:** Only one object varies.

**Laravel use case:** Multi-tenant branding kits, billing document formats, storage/report driver families.

**Files:** `src/Creational/AbstractFactory.php`, `examples/creational/abstract-factory.php`

**Run:** `php examples/creational/abstract-factory.php`

**Interview question:** What is the difference between Factory Method and Abstract Factory?

**Strong answer:** Factory Method creates one product through inheritance or an overridable method. Abstract Factory creates a family of related products through a factory interface.

**Common mistakes:** Creating a factory family for objects that do not need to vary together.

### Builder Pattern

**Problem:** Creating a report needs several optional settings and constructor calls become unreadable.

**Solution:** Use a fluent builder that accumulates configuration and creates a final object.

**When to use:** Object construction has many optional steps or needs validation before final creation.

**When not to use:** The object has two or three obvious constructor arguments.

**Laravel use case:** Query builders, mail builders, report export configuration.

**Files:** `src/Creational/Builder.php`, `examples/creational/builder.php`

**Run:** `php examples/creational/builder.php`

**Interview question:** Why does Laravel's query builder feel natural?

**Strong answer:** It uses a builder-like API to compose query parts fluently, then executes when requested.

**Common mistakes:** Making mutable builders leak into domain logic after `build()`.

### Singleton Pattern

**Problem:** Some object must appear globally unique.

**Solution:** Restrict construction and expose a shared instance.

**When to use:** Rarely, mostly for infrastructure with a truly single process-wide instance.

**When not to use:** Most application services. Prefer dependency injection.

**Laravel use case:** Laravel's container can manage singleton bindings without manual static global state.

**Files:** `src/Creational/Singleton.php`, `examples/creational/singleton.php`

**Run:** `php examples/creational/singleton.php`

**Interview question:** Why is Singleton controversial?

**Strong answer:** It hides dependencies, creates global state, and makes tests harder. In Laravel, container singletons are usually cleaner because dependencies remain injectable.

**Common mistakes:** Using Singleton for repositories, services, or request-scoped state.

### Prototype Pattern

**Problem:** Creating a configured object from scratch is expensive or repetitive.

**Solution:** Clone a prepared prototype and adjust only the differences.

**When to use:** You have template objects or complex defaults.

**When not to use:** Construction is simple or cloning can accidentally share mutable state.

**Laravel use case:** Cloning campaign templates, invoice templates, or preconfigured job payloads.

**Files:** `src/Creational/Prototype.php`, `examples/creational/prototype.php`

**Run:** `php examples/creational/prototype.php`

**Interview question:** What must you watch when cloning PHP objects?

**Strong answer:** PHP cloning is shallow by default, so nested objects may still be shared unless `__clone()` performs deep copy where needed.

**Common mistakes:** Forgetting to copy nested mutable objects.

## Structural Patterns

### Adapter Pattern

**Problem:** Existing code expects one interface, but a vendor or legacy client exposes another.

**Solution:** Wrap the incompatible object in a class that implements your expected interface.

**When to use:** Integrating third-party APIs, SDKs, old modules, or external services.

**When not to use:** You own both sides and can simply change the interface.

**Laravel use case:** Wrapping payment, SMS, storage, or analytics SDKs behind app-owned contracts.

**Files:** `src/Structural/Adapter.php`, `examples/structural/adapter.php`

**Run:** `php examples/structural/adapter.php`

**Interview question:** How do you keep vendor SDKs from spreading through your app?

**Strong answer:** I define a small application interface and adapt the SDK to it at the boundary.

**Common mistakes:** Matching the vendor API too closely and leaking vendor terminology.

### Decorator Pattern

**Problem:** You need to add behavior to an object without changing the object or subclassing repeatedly.

**Solution:** Wrap the object with another object that implements the same interface.

**When to use:** Logging, caching, retrying, tracing, authorization checks.

**When not to use:** The wrapper changes the core meaning of the object instead of extending behavior.

**Laravel use case:** Middleware, cache decorators, instrumented HTTP clients.

**Files:** `src/Structural/Decorator.php`, `examples/structural/decorator.php`

**Run:** `php examples/structural/decorator.php`

**Interview question:** How would you add request ids to logs without changing the logger?

**Strong answer:** Wrap the logger in a decorator that prepends request context and delegates to the original logger.

**Common mistakes:** Creating wrappers with different interfaces, which makes them adapters instead.

### Facade Pattern

**Problem:** A workflow requires many services and callers must know too much detail.

**Solution:** Expose a simple interface over a complex subsystem.

**When to use:** Simplifying a common workflow such as checkout, onboarding, or report generation.

**When not to use:** To hide poor domain boundaries or create a giant god service.

**Laravel use case:** Laravel facades such as `Cache`, `Log`, and `DB` provide static-looking access to container services.

**Files:** `src/Structural/Facade.php`, `examples/structural/facade.php`

**Run:** `php examples/structural/facade.php`

**Interview question:** Are Laravel facades the same as static classes?

**Strong answer:** They look static, but resolve services from the container, which keeps them more testable than plain static classes.

**Common mistakes:** Putting all business logic in a facade.

### Proxy Pattern

**Problem:** Direct access to an object is expensive, remote, sensitive, or needs access control.

**Solution:** Use a stand-in object with the same interface that controls access to the real object.

**When to use:** Caching, lazy loading, authorization, remote service access.

**When not to use:** There is no meaningful access-control or performance boundary.

**Laravel use case:** Lazy-loaded relations, cached repositories, access-controlled API clients.

**Files:** `src/Structural/Proxy.php`, `examples/structural/proxy.php`

**Run:** `php examples/structural/proxy.php`

**Interview question:** Difference between Facade and Proxy?

**Strong answer:** A facade simplifies a subsystem. A proxy has the same interface as the real subject and controls access to it.

**Common mistakes:** Calling every wrapper a proxy.

### Composite Pattern

**Problem:** Code needs to treat single items and groups uniformly.

**Solution:** Make leaves and containers implement the same interface.

**When to use:** Trees such as menus, permissions, categories, invoice groups.

**When not to use:** The data is flat.

**Laravel use case:** Nested categories, permission trees, menu builders.

**Files:** `src/Structural/Composite.php`, `examples/structural/composite.php`

**Run:** `php examples/structural/composite.php`

**Interview question:** How would you render nested menus cleanly?

**Strong answer:** Use a common node interface so menu items and groups can be rendered recursively.

**Common mistakes:** Making leaf objects know about children.

### Bridge Pattern

**Problem:** Two dimensions vary independently, such as notification type and delivery channel.

**Solution:** Split abstraction from implementation and compose them.

**When to use:** Avoiding class explosion like `EmailInvoicePaidNotification`, `SlackInvoicePaidNotification`, etc.

**When not to use:** Only one dimension changes.

**Laravel use case:** Notification classes plus channels resemble this idea.

**Files:** `src/Structural/Bridge.php`, `examples/structural/bridge.php`

**Run:** `php examples/structural/bridge.php`

**Interview question:** How do you avoid multiplying notification classes?

**Strong answer:** Keep notification content separate from delivery channel and compose them.

**Common mistakes:** Confusing Bridge with Adapter. Bridge is designed upfront; Adapter fixes incompatibility after the fact.

### Flyweight Pattern

**Problem:** Many small objects duplicate the same immutable data.

**Solution:** Share reusable intrinsic state and pass changing data externally.

**When to use:** Large collections, repeated formatters, permission descriptors, metadata.

**When not to use:** Object count is small or shared state is mutable.

**Laravel use case:** Reused value formatters, cached metadata, shared permission descriptors.

**Files:** `src/Structural/Flyweight.php`, `examples/structural/flyweight.php`

**Run:** `php examples/structural/flyweight.php`

**Interview question:** What problem does Flyweight optimize?

**Strong answer:** Memory and construction cost from repeated immutable objects.

**Common mistakes:** Sharing mutable state and causing hidden cross-request bugs.

## Behavioral Patterns

### Strategy Pattern

**Problem:** One algorithm varies, such as discounts or shipping rates.

**Solution:** Put each algorithm behind a shared interface and inject the chosen strategy.

**When to use:** Replacing conditionals with interchangeable behavior.

**When not to use:** The variation is tiny and unlikely to grow.

**Laravel use case:** Payment gateways, discount rules, shipping methods, tax calculators.

**Files:** `src/Behavioral/Strategy.php`, `examples/behavioral/strategy.php`

**Run:** `php examples/behavioral/strategy.php`

**Interview question:** How would you design multiple payment methods?

**Strong answer:** Create a payment strategy interface and one implementation per provider. Select the strategy at the boundary and keep checkout logic provider-agnostic.

**Common mistakes:** Using a strategy while still keeping a large `switch` inside the context.

### Observer Pattern

**Problem:** Multiple parts of the system must react when something happens.

**Solution:** A subject notifies registered observers.

**When to use:** Side effects after domain/application events.

**When not to use:** The main transaction depends on strict ordered results from observers.

**Laravel use case:** Eloquent observers, events and listeners.

**Files:** `src/Behavioral/Observer.php`, `examples/behavioral/observer.php`

**Run:** `php examples/behavioral/observer.php`

**Interview question:** When would you use observers in Laravel?

**Strong answer:** For side effects like sending receipts or analytics after a model change, while keeping core domain logic explicit.

**Common mistakes:** Hiding critical business rules in observers.

### Command Pattern

**Problem:** You need to represent an operation as an object.

**Solution:** Encapsulate the request and execute it through a handler or bus.

**When to use:** Queues, retries, undoable actions, command buses.

**When not to use:** A direct method call is clearer.

**Laravel use case:** Jobs, commands, queued tasks.

**Files:** `src/Behavioral/Command.php`, `examples/behavioral/command.php`

**Run:** `php examples/behavioral/command.php`

**Interview question:** Why are Laravel jobs command-like?

**Strong answer:** They package all data needed to perform work and expose a `handle()` method that can run later.

**Common mistakes:** Putting too much orchestration into one command.

### Chain of Responsibility Pattern

**Problem:** A request may be handled by one of several handlers.

**Solution:** Pass the request through a chain until a handler accepts it.

**When to use:** Validation pipelines, middleware, support routing.

**When not to use:** The handler is known directly.

**Laravel use case:** HTTP middleware pipeline.

**Files:** `src/Behavioral/ChainOfResponsibility.php`, `examples/behavioral/chain-of-responsibility.php`

**Run:** `php examples/behavioral/chain-of-responsibility.php`

**Interview question:** What Laravel feature is a strong Chain example?

**Strong answer:** Middleware, because each layer can handle, reject, modify, or pass the request onward.

**Common mistakes:** Making order implicit and hard to test.

### Template Method Pattern

**Problem:** Algorithms share fixed steps, but some steps vary.

**Solution:** Put the invariant workflow in a base class and let subclasses customize selected steps.

**When to use:** Import/export pipelines, report generation, parsers.

**When not to use:** Composition would be simpler or inheritance would become deep.

**Laravel use case:** Base import classes, abstract report generators.

**Files:** `src/Behavioral/TemplateMethod.php`, `examples/behavioral/template-method.php`

**Run:** `php examples/behavioral/template-method.php`

**Interview question:** What risk comes with Template Method?

**Strong answer:** It relies on inheritance, so it can become rigid. Use it when the algorithm skeleton is genuinely stable.

**Common mistakes:** Overusing inheritance for workflows that need composition.

### State Pattern

**Problem:** An object behaves differently depending on its current state.

**Solution:** Move state-specific behavior into state classes.

**When to use:** Order, payment, subscription, or ticket workflows.

**When not to use:** State transitions are simple and stable.

**Laravel use case:** Order lifecycle services, subscription status objects.

**Files:** `src/Behavioral/State.php`, `examples/behavioral/state.php`

**Run:** `php examples/behavioral/state.php`

**Interview question:** Difference between Strategy and State?

**Strong answer:** Strategy is usually selected from outside to vary an algorithm. State changes internally as the object transitions through a lifecycle.

**Common mistakes:** Keeping transition rules duplicated in controllers.

### Iterator Pattern

**Problem:** You need to traverse a collection without exposing its internal structure.

**Solution:** Provide an iterator interface or implement `IteratorAggregate`.

**When to use:** Domain collections, paged API results, invoice lines.

**When not to use:** A plain array is enough and no behavior is attached.

**Laravel use case:** Collections, lazy collections, cursor iteration.

**Files:** `src/Behavioral/Iterator.php`, `examples/behavioral/iterator.php`

**Run:** `php examples/behavioral/iterator.php`

**Interview question:** Why use a collection object instead of an array?

**Strong answer:** To preserve invariants and expose meaningful traversal without leaking storage details.

**Common mistakes:** Creating collection classes that add no behavior.

### Mediator Pattern

**Problem:** Components communicate directly and become tightly coupled.

**Solution:** Centralize collaboration in a mediator.

**When to use:** Complex workflows with many peer components.

**When not to use:** It becomes a god object.

**Laravel use case:** Application services coordinating inventory, payment, and notifications.

**Files:** `src/Behavioral/Mediator.php`, `examples/behavioral/mediator.php`

**Run:** `php examples/behavioral/mediator.php`

**Interview question:** How do you reduce coupling in checkout orchestration?

**Strong answer:** Put coordination in an application-level mediator/service and keep components focused on their own responsibilities.

**Common mistakes:** Putting domain decisions and infrastructure details in one mediator.

### Specification Pattern

**Problem:** Business rules need to be reusable and composable.

**Solution:** Express each rule as an object with `isSatisfiedBy()`.

**When to use:** Eligibility, permissions, discounts, filtering rules.

**When not to use:** A simple inline condition is clearer.

**Laravel use case:** Discount eligibility rules, user access policies, reusable query predicates.

**Files:** `src/Behavioral/Specification.php`, `examples/behavioral/specification.php`

**Run:** `php examples/behavioral/specification.php`

**Interview question:** How do you avoid duplicated eligibility checks?

**Strong answer:** Move each rule into a specification and compose rules for higher-level policies.

**Common mistakes:** Building a large rule engine when a few methods would do.

## PHP/Laravel Architectural Patterns

### Repository Pattern

**Problem:** Business code should not depend directly on persistence details.

**Solution:** Put data access behind a repository interface.

**When to use:** Complex persistence, external data stores, testable domain boundaries.

**When not to use:** Thin CRUD over Eloquent with no added behavior.

**Laravel use case:** Useful for domain-heavy modules; often overused as empty wrappers around Eloquent.

**Files:** `src/Architectural/Repository.php`, `examples/architectural/repository.php`

**Run:** `php examples/architectural/repository.php`

**Interview question:** Should every Eloquent model have a repository?

**Strong answer:** No. Use repositories when they create a useful boundary or hide complex data access, not as mandatory boilerplate.

**Common mistakes:** One repository per model with pass-through CRUD methods.

### Service Layer Pattern

**Problem:** Controllers become overloaded with business rules.

**Solution:** Move application business workflows into services.

**When to use:** Multi-step use cases, transaction boundaries, reusable business processes.

**When not to use:** Trivial controller actions with no business logic.

**Laravel use case:** Checkout services, billing services, subscription services.

**Files:** `src/Architectural/ServiceLayer.php`, `examples/architectural/service-layer.php`

**Run:** `php examples/architectural/service-layer.php`

**Interview question:** What belongs in a service class?

**Strong answer:** Application workflow and business orchestration, not HTTP request parsing or view formatting.

**Common mistakes:** Creating vague `Manager` classes with unrelated methods.

### Action Class Pattern

**Problem:** A use case deserves a focused class but a large service would be too broad.

**Solution:** Create one class for one application action.

**When to use:** Single-purpose use cases such as cancel order, invite user, capture payment.

**When not to use:** The action has no logic beyond calling one model method.

**Laravel use case:** Invokable classes called from controllers, jobs, or commands.

**Files:** `src/Architectural/ActionClass.php`, `examples/architectural/action-class.php`

**Run:** `php examples/architectural/action-class.php`

**Interview question:** Difference between Command and Action Class?

**Strong answer:** A command represents an operation, often dispatched or queued. An action class is a direct application use-case handler.

**Common mistakes:** Making every method in the app its own action.

### DTO Pattern

**Problem:** Passing arrays around makes data shape implicit and fragile.

**Solution:** Use a typed object to transfer input or output data.

**When to use:** Request data, API payloads, service input, responses across boundaries.

**When not to use:** Data is already a rich domain object.

**Laravel use case:** Form request to service data, API response data, job payloads.

**Files:** `src/Architectural/Dto.php`, `examples/architectural/dto.php`

**Run:** `php examples/architectural/dto.php`

**Interview question:** Why use DTOs instead of arrays?

**Strong answer:** DTOs make fields explicit, typed, discoverable, and safer to refactor.

**Common mistakes:** Adding domain behavior to DTOs.

### Value Object Pattern

**Problem:** Primitive strings and integers allow invalid states.

**Solution:** Wrap a concept and its validation in an immutable object.

**When to use:** Email, money, date ranges, SKU, coordinates.

**When not to use:** The value has no invariant or meaning beyond a primitive.

**Laravel use case:** Casts for Money, EmailAddress, Address, DateRange.

**Files:** `src/Architectural/ValueObject.php`, `examples/architectural/value-object.php`

**Run:** `php examples/architectural/value-object.php`

**Interview question:** Difference between DTO and Value Object?

**Strong answer:** A DTO carries data across boundaries. A value object models a domain concept, validates invariants, and is usually immutable.

**Common mistakes:** Making value objects mutable.

### Domain Event Pattern

**Problem:** Domain actions need follow-up work without coupling the aggregate to every side effect.

**Solution:** Record an event describing something that already happened.

**When to use:** Order paid, user registered, invoice overdue, subscription cancelled.

**When not to use:** For commands asking the system to do something.

**Laravel use case:** Events/listeners, queued listeners, event sourcing foundations.

**Files:** `src/Architectural/DomainEvent.php`, `examples/architectural/domain-event.php`

**Run:** `php examples/architectural/domain-event.php`

**Interview question:** Difference between Observer and Domain Event?

**Strong answer:** Observer is a notification mechanism. A domain event is a domain fact that can be published through observers/listeners.

**Common mistakes:** Naming events as commands, like `SendEmail`, instead of facts, like `OrderWasPaid`.

### Query Object Pattern

**Problem:** Complex query filters get duplicated across controllers and services.

**Solution:** Encapsulate a query or filter as an object.

**When to use:** Reusable reporting filters, dashboard queries, search screens.

**When not to use:** One simple local query.

**Laravel use case:** Invokable Eloquent query filters, scopes, report query classes.

**Files:** `src/Architectural/QueryObject.php`, `examples/architectural/query-object.php`

**Run:** `php examples/architectural/query-object.php`

**Interview question:** How do you avoid duplicated Eloquent filters?

**Strong answer:** Use scopes for model-local filters and query objects for reusable application-level queries.

**Common mistakes:** Returning raw query builders from too many layers without clear ownership.
